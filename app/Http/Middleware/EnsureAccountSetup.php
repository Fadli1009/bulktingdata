<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAccountSetup
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        if (! $user->hasChosenAccountType()) {
            return redirect()->route('account.type');
        }

        if (! $user->hasCompletedProfile()) {
            return redirect()->route('account.complete');
        }

        return $next($request);
    }
}
