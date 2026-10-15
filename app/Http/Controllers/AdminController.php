<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminController extends Controller
{
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

    public function orders(): View
    {
        return view('admin.orders.index');
    }

    public function packages(): View
    {
        return view('admin.packages.index');
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
