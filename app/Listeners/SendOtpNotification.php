<?php

namespace App\Listeners;

use App\Events\OtpRequested;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class SendOtpNotification implements ShouldQueue
{
    public function handle(OtpRequested $event): void
    {
        // Hook point for SMS/email provider integrations.
        Log::info('Checkout OTP requested', [
            'otp_id' => $event->otp->id,
            'email' => $event->otp->email,
            'phone' => $event->otp->phone,
            'otp_preview' => app()->isLocal() ? $event->plainOtp : 'hidden',
        ]);
    }
}
