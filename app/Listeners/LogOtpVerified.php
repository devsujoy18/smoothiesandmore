<?php

namespace App\Listeners;

use App\Events\OtpVerified;
use Illuminate\Support\Facades\Log;

class LogOtpVerified
{
    public function handle(OtpVerified $event): void
    {
        Log::info('Checkout OTP verified', [
            'otp_id' => $event->otp->id,
            'email' => $event->otp->email,
            'phone' => $event->otp->phone,
        ]);
    }
}
