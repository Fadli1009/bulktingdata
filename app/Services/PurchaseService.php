<?php

namespace App\Services;

use App\Enums\BillingPeriod;
use App\Enums\OrderStatus;
use App\Enums\SubscriptionStatus;
use App\Models\Order;
use App\Models\Package;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PurchaseService
{
    /**
     * Buat order baru berstatus pending untuk sebuah paket.
     */
    public function createOrder(User $user, Package $package): Order
    {
        return Order::create([
            'order_number' => $this->generateOrderNumber(),
            'user_id' => $user->id,
            'package_id' => $package->id,
            'ordered_by' => $user->id,
            'package_name' => $package->name,
            'package_type' => $package->type,
            'total' => $package->price,
            'status' => OrderStatus::Pending,
            'ordered_at' => now(),
        ]);
    }

    /**
     * Simpan bukti transfer dan tandai order menunggu verifikasi.
     *
     * @throws ValidationException
     */
    public function submitPayment(Order $order, UploadedFile $proof, ?string $reference = null): void
    {
        if (! $order->isPending() && ! $order->isProcessing()) {
            throw ValidationException::withMessages([
                'proof' => 'Order ini tidak lagi menerima bukti pembayaran.',
            ]);
        }

        $path = $proof->store(
            config('payments.proof_directory', 'payment-proofs'),
            config('payments.proof_disk', 'local'),
        );

        $order->update([
            'payment_proof_path' => $path,
            'payment_reference' => $reference,
            'paid_at' => now(),
            'status' => OrderStatus::Processing,
        ]);
    }

    /**
     * Konfirmasi pembayaran dan aktifkan/perpanjang langganan pemilik akun.
     *
     * @throws ValidationException
     */
    public function confirm(Order $order): Subscription
    {
        if ($order->isDone()) {
            throw ValidationException::withMessages([
                'order' => 'Order ini sudah dikonfirmasi sebelumnya.',
            ]);
        }

        if ($order->isCancelled()) {
            throw ValidationException::withMessages([
                'order' => 'Order ini sudah dibatalkan.',
            ]);
        }

        return DB::transaction(function () use ($order): Subscription {
            $order->update([
                'status' => OrderStatus::Done,
                'confirmed_at' => now(),
            ]);

            return $this->activateSubscription($order);
        });
    }

    /**
     * Batalkan order yang belum dikonfirmasi.
     *
     * @throws ValidationException
     */
    public function cancel(Order $order): void
    {
        if ($order->isDone()) {
            throw ValidationException::withMessages([
                'order' => 'Order yang sudah dikonfirmasi tidak bisa dibatalkan.',
            ]);
        }

        $order->update(['status' => OrderStatus::Cancelled]);
    }

    /**
     * Buat langganan aktif baru, memperpanjang dari tanggal berakhir bila ada.
     */
    protected function activateSubscription(Order $order): Subscription
    {
        $owner = $order->customer?->subscriptionOwner() ?? $order->customer;

        $current = $owner?->activeSubscription()->first();

        $base = ($current && $current->ends_at && $current->ends_at->isFuture())
            ? $current->ends_at
            : now();

        $period = $order->package?->billing_period ?? BillingPeriod::Monthly;

        if ($current) {
            $current->update(['status' => SubscriptionStatus::Cancelled]);
        }

        return Subscription::create([
            'user_id' => $owner->id,
            'package_id' => $order->package_id,
            'order_id' => $order->id,
            'status' => SubscriptionStatus::Active,
            'starts_at' => now(),
            'ends_at' => $period->addTo($base),
        ]);
    }

    /**
     * Hasilkan nomor order unik dengan format INV-YYYYMMDD-XXXX.
     */
    protected function generateOrderNumber(): string
    {
        do {
            $number = 'INV-'.now()->format('Ymd').'-'.str_pad((string) random_int(0, 9999), 4, '0', STR_PAD_LEFT);
        } while (Order::where('order_number', $number)->exists());

        return $number;
    }
}
