<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class AdminController extends Controller
{
    public function dashboard(): View
    {
        return view('admin.dashboard');
    }

    public function customers(): View
    {
        return view('admin.customers.index');
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
