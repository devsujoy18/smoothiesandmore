<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CheckoutAuthService
{
    public function loginOrRegister(string $email, string $phone): User
    {
        return DB::transaction(function () use ($email, $phone) {
            $user = User::query()->where('email', $email)->first();

            if (! $user) {
                $name = Str::headline(Str::before($email, '@'));
                $user = User::create([
                    'name' => $name ?: 'Customer',
                    'email' => $email,
                    'phone' => $phone,
                    'password' => Hash::make(Str::random(40)),
                    'role' => User::ROLE_USER,
                    'email_verified_at' => now(),
                ]);
            } else {
                $user->forceFill([
                    'phone' => $user->phone ?: $phone,
                ])->save();
            }

            Auth::login($user, true);
            session()->regenerate();

            return $user;
        });
    }
}

