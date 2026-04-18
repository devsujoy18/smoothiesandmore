<?php

namespace App\Events;

use App\Models\Otp;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OtpRequested
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Otp $otp,
        public string $plainOtp,
    ) {}
}
