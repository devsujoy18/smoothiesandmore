<?php

namespace App\Providers;

use App\Models\Address;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureDefaults();

        Gate::define('update-address', function (User $user, Address $address) {
            return $user->id === $address->user_id;
        });

        Gate::define('delete-address', function (User $user, Address $address) {
            return $user->id === $address->user_id;
        });

        Gate::define('set-default-address', function (User $user, Address $address) {
            return $user->id === $address->user_id;
        });
    }

    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null
        );

        RateLimiter::for('otp-send', function (Request $request) {
            $key = strtolower((string) $request->input('email')).'|'.(string) $request->input('phone').'|'.$request->ip();

            return Limit::perMinute(3)->by($key);
        });

        RateLimiter::for('otp-verify', function (Request $request) {
            $key = strtolower((string) $request->input('email')).'|'.(string) $request->input('phone').'|'.$request->ip();

            return Limit::perMinute(10)->by($key);
        });
    }
}
