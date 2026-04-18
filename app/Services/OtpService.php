<?php

namespace App\Services;

use App\Events\OtpRequested;
use App\Events\OtpVerified;
use App\Models\Otp;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class OtpService
{
    private const OTP_EXPIRY_MINUTES = 5;
    private const MAX_RESEND = 3;
    private const MAX_VERIFY_ATTEMPTS = 5;

    public function send(string $email, string $phone): Otp
    {
        $latest = Otp::query()
            ->where('email', $email)
            ->where('phone', $phone)
            ->latest('id')
            ->first();

        if ($latest && $latest->resend_count >= self::MAX_RESEND && $latest->verified_at === null) {
            throw ValidationException::withMessages([
                'otp' => 'Maximum OTP resend attempts reached. Please try again later.',
            ]);
        }

        //$otpCode = (string) random_int(100000, 999999);
        $otpCode = (string) 123456;

        $otp = Otp::create([
            'email' => $email,
            'phone' => $phone,
            'otp_hash' => Hash::make($otpCode),
            'attempts' => 0,
            'resend_count' => ($latest?->resend_count ?? 0) + 1,
            'expires_at' => now()->addMinutes(self::OTP_EXPIRY_MINUTES),
        ]);

        event(new OtpRequested($otp, $otpCode));

        return $otp;
    }

    public function verify(string $email, string $phone, string $otpCode): Otp
    {
        $otp = Otp::query()
            ->where('email', $email)
            ->where('phone', $phone)
            ->active()
            ->latest('id')
            ->first();

        if (! $otp) {
            throw ValidationException::withMessages([
                'otp' => 'OTP not found or expired. Please request a new OTP.',
            ]);
        }

        if ($otp->attempts >= self::MAX_VERIFY_ATTEMPTS) {
            throw ValidationException::withMessages([
                'otp' => 'Too many invalid attempts. Please request a new OTP.',
            ]);
        }

        if (! Hash::check($otpCode, $otp->otp_hash)) {
            $otp->increment('attempts');

            throw ValidationException::withMessages([
                'otp' => 'Invalid OTP. Please try again.',
            ]);
        }

        $otp->forceFill([
            'verified_at' => now(),
        ])->save();

        event(new OtpVerified($otp));

        return $otp;
    }
}

