<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\PurchaseService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class OrderController extends Controller
{
    public function __construct(protected PurchaseService $purchaseService) {}

    public function index(Request $request): View
    {
        $orders = Order::query()
            ->where('user_id', $request->user()->id)
            ->with('package')
            ->latest('ordered_at')
            ->paginate(15)
            ->withQueryString();

        return view('orders.index', compact('orders'));
    }

    public function show(Request $request, Order $order): View
    {
        $this->authorizeOrder($request, $order);

        $order->load(['package', 'subscription.package']);

        return view('orders.show', compact('order'));
    }

    public function submitProof(Request $request, Order $order): RedirectResponse
    {
        $this->authorizeOrder($request, $order);

        $validated = $request->validate([
            'proof' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:'.config('payments.proof_max_kilobytes', 5120)],
            'payment_reference' => ['nullable', 'string', 'max:255'],
        ]);

        $this->purchaseService->submitPayment(
            $order,
            $validated['proof'],
            $validated['payment_reference'] ?? null,
        );

        return back()->with('success', 'Bukti transfer terkirim. Menunggu verifikasi admin.');
    }

    public function downloadProof(Request $request, Order $order): StreamedResponse
    {
        $this->authorizeOrder($request, $order);

        abort_unless($order->payment_proof_path, 404);

        $disk = config('payments.proof_disk', 'local');

        abort_unless(Storage::disk($disk)->exists($order->payment_proof_path), 404);

        return Storage::disk($disk)->download($order->payment_proof_path);
    }

    protected function authorizeOrder(Request $request, Order $order): void
    {
        abort_unless($order->user_id === $request->user()->id, 403);
    }
}
