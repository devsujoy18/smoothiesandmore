<?php

namespace App\Events;

use App\Models\Otp;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OtpVerified
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Otp $otp,
    ) {
    }
}

