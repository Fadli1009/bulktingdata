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

class AuthController extends Controller
{
    public function __construct(protected OtpService $otpService) {}

    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function redirectToGoogle(Request $request): RedirectResponse
    {
        $request->session()->put('auth_intent', 'user');

        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback(Request $request): RedirectResponse
    {
        $intent = $request->session()->pull('auth_intent', 'user');

        try {
            $googleUser = Socialite::driver('google')->user();

            if ($intent === 'admin') {
                $admin = User::where('email', $googleUser->email)->where('is_admin', true)->first();

                if (! $admin) {
                    return redirect()->route('admin.login')->with('error', 'Akun Google ini tidak terdaftar sebagai admin.');
                }

                $admin->update([
                    'google_id' => $googleUser->id,
                    'avatar' => $googleUser->avatar,
                    'email_verified_at' => now(),
                ]);

                Auth::login($admin);

                return redirect('/admin');
            }

            $user = User::updateOrCreate(
                ['google_id' => $googleUser->id],
                [
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'avatar' => $googleUser->avatar,
                    'email_verified_at' => now(),
                ]
            );

            Auth::login($user);

            return redirect('/');
        } catch (\Throwable $e) {
            report($e);

            $route = $intent === 'admin' ? 'admin.login' : 'login';

            return redirect()->route($route)->with('error', 'Gagal masuk dengan Google. Silakan coba lagi.');
        }
    }

    public function sendOtp(Request $request)
    {
        $request->validate(['email' => 'required|email|max:255']);

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

        try {
            $this->otpService->verify($request->email, $request->code);

            $user = User::updateOrCreate(
                ['email' => $request->email],
                [
                    'name' => $request->email,
                    'email_verified_at' => now(),
                ]
            );

            Auth::login($user);

            return $this->redirectAfterAuth($user, $request);
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }
    }

    public function showAccountType(): View|RedirectResponse
    {
        $user = Auth::user();

        if ($user->hasChosenAccountType()) {
            return redirect()->route('account.complete');
        }

        return view('account.type');
    }

    public function storeAccountType(Request $request): RedirectResponse
    {
        $request->validate(['account_type' => 'required|in:personal,bisnis']);

        Auth::user()->update(['account_type' => $request->account_type]);

        return redirect()->route('account.complete');
    }

    public function showCompleteForm(): View|RedirectResponse
    {
        $user = Auth::user();

        if (! $user->hasChosenAccountType()) {
            return redirect()->route('account.type');
        }

        if ($user->hasCompletedProfile()) {
            return redirect('/admin');
        }

        $view = $user->isPersonal() ? 'account.complete-personal' : 'account.complete-bisnis';

        return view($view);
    }

    public function storeCompleteForm(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $rules = $user->isPersonal()
            ? ['name' => 'required|string|max:255', 'phone' => 'nullable|string|max:20']
            : ['name' => 'required|string|max:255', 'phone' => 'nullable|string|max:20'];

        $validated = $request->validate($rules);

        $user->update([
            'name' => $validated['name'],
            'phone' => $validated['phone'] ?? null,
            'profile_completed_at' => now(),
        ]);

        return redirect('/')->with('success', 'Profil Anda berhasil dilengkapi!');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    protected function redirectAfterAuth(User $user, Request $request): RedirectResponse
    {
        $intent = $request->session()->pull('auth_intent', 'user');

        if ($intent === 'admin') {
            return redirect('/admin');
        }

        return redirect('/');
    }
}
