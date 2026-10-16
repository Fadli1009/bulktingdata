<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Services\PurchaseService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function __construct(protected PurchaseService $purchaseService) {}

    public function show(Package $package): View
    {
        abort_unless($package->status === 'active', 404);

        return view('checkout.show', compact('package'));
    }

    public function store(Request $request, Package $package): RedirectResponse
    {
        abort_unless($package->status === 'active', 404);

        $order = $this->purchaseService->createOrder($request->user(), $package);

        return redirect()
            ->route('orders.show', $order)
            ->with('success', 'Pesanan dibuat. Silakan selesaikan pembayaran.');
    }
}
