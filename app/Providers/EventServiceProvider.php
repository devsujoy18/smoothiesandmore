<?php

namespace App\Providers;

use App\Events\OrderPlaced;
use App\Events\OtpRequested;
use App\Events\OtpVerified;
use App\Listeners\LogOrderPlaced;
use App\Listeners\LogOtpVerified;
use App\Listeners\SendOtpNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        OtpRequested::class => [
            SendOtpNotification::class,
        ],
        OtpVerified::class => [
            LogOtpVerified::class,
        ],
        OrderPlaced::class => [
            LogOrderPlaced::class,
        ],
    ];
}
