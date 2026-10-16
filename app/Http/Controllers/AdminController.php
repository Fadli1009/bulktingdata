<?php

namespace App\Http\Controllers;

use App\Enums\BillingPeriod;
use App\Enums\OrderStatus;
use App\Enums\PackageType;
use App\Models\Order;
use App\Models\Package;
use App\Models\User;
use App\Services\PurchaseService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminController extends Controller
{
    public function __construct(protected PurchaseService $purchaseService) {}

    public function dashboard(): View
    {
        $stats = [
            'customers' => User::query()->customers()->count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }

    public function customers(Request $request): View
    {
        $search = $request->string('q')->toString();
        $status = $request->string('status')->toString();

        $customers = User::query()
            ->customers()
            ->when($search !== '', fn ($query) => $query->where(
                fn ($query) => $query->whereLike('name', "%{$search}%")
                    ->orWhereLike('email', "%{$search}%"),
            ))
            ->when($status === 'success', fn ($query) => $query->whereNotNull('profile_completed_at'))
            ->when($status === 'pending', fn ($query) => $query->whereNull('profile_completed_at'))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.customers.index', compact('customers', 'search', 'status'));
    }

    public function customerCreate(): View
    {
        return view('admin.customers.create');
    }

    public function orders(Request $request): View
    {
        $status = $request->string('status')->toString();
        $customerId = $request->string('customer_id')->toString();

        $orders = Order::query()
            ->with(['customer', 'package', 'orderedBy'])
            ->when($customerId !== '', fn ($query) => $query->where('user_id', $customerId))
            ->when($status !== '', fn ($query) => $query->where('status', $status))
            ->when($request->filled('from'), fn ($query) => $query->whereDate('ordered_at', '>=', $request->date('from')))
            ->when($request->filled('to'), fn ($query) => $query->whereDate('ordered_at', '<=', $request->date('to')))
            ->latest('ordered_at')
            ->paginate(15)
            ->withQueryString();

        $customerOptions = User::query()->customers()->orderBy('name')->get(['id', 'name']);

        $statusLabel = collect(OrderStatus::cases())->mapWithKeys(fn ($case) => [$case->value => $case->label()]);

        return view('admin.orders.index', compact('orders', 'customerOptions', 'statusLabel', 'status', 'customerId'));
    }

    public function orderStatus(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', Rule::enum(OrderStatus::class)],
        ]);

        $order->update(['status' => OrderStatus::from($validated['status'])]);

        return back()->with('success', 'Status order berhasil diperbarui.');
    }

    public function orderConfirm(Order $order): RedirectResponse
    {
        try {
            $this->purchaseService->confirm($order);
        } catch (ValidationException $e) {
            return back()->with('error', $e->validator->errors()->first());
        }

        return back()->with('success', 'Pembayaran dikonfirmasi. Langganan pelanggan telah aktif.');
    }

    public function orderCancel(Order $order): RedirectResponse
    {
        try {
            $this->purchaseService->cancel($order);
        } catch (ValidationException $e) {
            return back()->with('error', $e->validator->errors()->first());
        }

        return back()->with('success', 'Order berhasil dibatalkan.');
    }

    public function orderProof(Order $order): StreamedResponse
    {
        abort_unless($order->payment_proof_path, 404);

        $disk = config('payments.proof_disk', 'local');

        abort_unless(Storage::disk($disk)->exists($order->payment_proof_path), 404);

        return Storage::disk($disk)->download($order->payment_proof_path);
    }

    public function packages(Request $request): View
    {
        $type = $request->string('type')->toString();

        $packages = Package::query()
            ->withCount('orders')
            ->when(
                $type !== '' && in_array($type, ['personal', 'bisnis'], true),
                fn ($query) => $query->ofType(PackageType::from($type)),
            )
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('admin.packages.index', compact('packages', 'type'));
    }

    public function packageStore(Request $request): RedirectResponse
    {
        Package::create($this->packageData($request));

        return back()->with('success', 'Paket berhasil ditambahkan.');
    }

    public function packageUpdate(Request $request, Package $package): RedirectResponse
    {
        $package->update($this->packageData($request));

        return back()->with('success', 'Paket berhasil diperbarui.');
    }

    public function packageToggle(Package $package): RedirectResponse
    {
        $package->update([
            'status' => $package->status === 'active' ? 'inactive' : 'active',
        ]);

        return back()->with('success', 'Status paket berhasil diubah.');
    }

    public function packageDestroy(Package $package): RedirectResponse
    {
        $package->delete();

        return back()->with('success', 'Paket berhasil dihapus.');
    }

    /**
     * Validate and normalize package input from the admin form.
     *
     * @return array<string, mixed>
     */
    protected function packageData(Request $request): array
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'type' => ['required', Rule::enum(PackageType::class)],
            'price' => ['required', 'integer', 'min:0'],
            'billing_period' => ['required', Rule::enum(BillingPeriod::class)],
            'upload_limit' => ['nullable', 'integer', 'min:0'],
            'seat_limit' => ['nullable', 'integer', 'min:0'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'badge' => ['nullable', 'string', 'max:50'],
            'is_featured' => ['boolean'],
            'features' => ['nullable', 'string', 'max:2000'],
        ]);

        $isBusiness = $validated['type'] === PackageType::Business->value;

        return [
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'type' => $validated['type'],
            'price' => $validated['price'],
            'billing_period' => $validated['billing_period'],
            'upload_limit' => $isBusiness ? null : ($validated['upload_limit'] ?? null),
            'seat_limit' => $isBusiness ? ($validated['seat_limit'] ?? null) : null,
            'status' => $validated['status'],
            'sort_order' => $validated['sort_order'] ?? 0,
            'badge' => $validated['badge'] ?? null,
            'is_featured' => $request->boolean('is_featured'),
            'features' => $this->parseFeatures($validated['features'] ?? null),
        ];
    }

    /**
     * Turn a newline separated textarea into a list of features.
     *
     * @return array<int, string>|null
     */
    protected function parseFeatures(?string $features): ?array
    {
        if ($features === null) {
            return null;
        }

        $lines = collect(preg_split('/\r\n|\r|\n/', $features))
            ->map(fn (string $line) => trim($line))
            ->filter()
            ->values()
            ->all();

        return $lines === [] ? null : $lines;
    }

    public function faqs(): View
    {
        return view('admin.faqs.index');
    }

    public function uploads(): View
    {
        return view('admin.uploads.index');
    }

    public function logs(): View
    {
        return view('admin.logs.index');
    }
}
