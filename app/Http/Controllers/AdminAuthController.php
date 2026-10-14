<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\OtpService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Laravel\Socialite\Facades\Socialite;

class AdminAuthController extends Controller
{
    public function __construct(protected OtpService $otpService) {}

    public function showLogin(): View
    {
        return view('admin.auth.login');
    }

    public function redirectToGoogle(Request $request): RedirectResponse
    {
        $request->session()->put('auth_intent', 'admin');

        return Socialite::driver('google')->redirect();
    }

    public function sendOtp(Request $request)
    {
        $request->validate(['email' => 'required|email|max:255']);

        if (! User::where('email', $request->email)->where('is_admin', true)->exists()) {
            throw ValidationException::withMessages([
                'email' => 'Email ini tidak terdaftar sebagai admin.',
            ]);
        }

        try {
            $this->otpService->send($request->email, $request->ip());

            if ($request->expectsJson()) {
                return response()->json(['message' => 'Kode OTP telah dikirim ke email Anda.']);
            }

            return back()->with('otp_sent', $request->email);
        } catch (ValidationException $e) {
            if ($request->expectsJson()) {
                return response()->json(['errors' => $e->errors()], 422);
            }

            throw $e;
        }
    }

    public function verifyOtp(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email',
            'code' => 'required|digits:6',
        ]);

        $admin = User::where('email', $request->email)->where('is_admin', true)->first();

        if (! $admin) {
            return back()->withErrors(['email' => 'Email ini tidak terdaftar sebagai admin.'])->withInput();
        }

        try {
            $this->otpService->verify($request->email, $request->code);

            Auth::login($admin);

            return redirect('/admin');
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }
    }
}
