<?php

namespace App\Services;

use App\Mail\OtpMail;
use App\Models\OtpToken;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class OtpService
{
    /** Masa berlaku kode OTP (menit). */
    public const EXPIRY_MINUTES = 10;

    /** Jeda minimum antar permintaan OTP (detik). */
    public const RESEND_COOLDOWN_SECONDS = 60;

    /** Batas maksimum permintaan OTP per email per hari. */
    public const MAX_PER_DAY = 3;

    /**
     * Buat & kirim kode OTP ke email tujuan.
     *
     * @throws ValidationException
     */
    public function send(string $email, ?string $ip = null): OtpToken
    {
        $email = strtolower(trim($email));

        $last = OtpToken::where('email', $email)->latest('id')->first();

        if ($last && $last->created_at->diffInSeconds(now()) < self::RESEND_COOLDOWN_SECONDS) {
            $wait = self::RESEND_COOLDOWN_SECONDS - $last->created_at->diffInSeconds(now());

            throw ValidationException::withMessages([
                'email' => "Mohon tunggu {$wait} detik sebelum meminta kode baru.",
            ]);
        }

        $todayCount = OtpToken::where('email', $email)
            ->whereDate('created_at', today())
            ->count();

        if ($todayCount >= self::MAX_PER_DAY) {
            throw ValidationException::withMessages([
                'email' => 'Batas permintaan kode OTP hari ini telah tercapai. Coba lagi besok.',
            ]);
        }

        $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $otp = OtpToken::create([
            'email' => $email,
            'token' => Hash::make($code),
            'expires_at' => now()->addMinutes(self::EXPIRY_MINUTES),
            'ip_address' => $ip,
        ]);

        Mail::to($email)->send(new OtpMail($code, self::EXPIRY_MINUTES));

        return $otp;
    }

    /**
     * Verifikasi kode OTP. Mengembalikan token bila valid.
     *
     * @throws ValidationException
     */
    public function verify(string $email, string $code): OtpToken
    {
        $email = strtolower(trim($email));

        $otp = OtpToken::where('email', $email)
            ->valid()
            ->latest('id')
            ->first();

        if (! $otp || $otp->isExpired()) {
            throw ValidationException::withMessages([
                'code' => 'Kode OTP tidak ditemukan atau sudah kedaluwarsa. Silakan minta kode baru.',
            ]);
        }

        if (! Hash::check($code, $otp->token)) {
            throw ValidationException::withMessages([
                'code' => 'Kode OTP yang Anda masukkan salah.',
            ]);
        }

        $otp->forceFill(['used_at' => now()])->save();

        return $otp;
    }
}
