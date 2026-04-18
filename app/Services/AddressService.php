<?php

namespace App\Services;

use App\Models\Address;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AddressService
{
    private const MAX_ADDRESSES = 3;

    public function create(User $user, array $data): Address
    {
        if ($user->addresses()->count() >= self::MAX_ADDRESSES) {
            throw ValidationException::withMessages([
                'address' => 'You can add a maximum of 3 addresses.',
            ]);
        }

        return DB::transaction(function () use ($user, $data) {
            $isDefault = (bool) ($data['is_default'] ?? false);

            if (! $user->addresses()->exists()) {
                $isDefault = true;
            }

            if ($isDefault) {
                $user->addresses()->update(['is_default' => false]);
            }

            return $user->addresses()->create([
                ...$data,
                'is_default' => $isDefault,
            ]);
        });
    }

    public function update(Address $address, array $data): Address
    {
        return DB::transaction(function () use ($address, $data) {
            $isDefault = (bool) ($data['is_default'] ?? false);

            if ($isDefault) {
                $address->user->addresses()->update(['is_default' => false]);
            }

            $address->update([
                ...$data,
                'is_default' => $isDefault ?: $address->is_default,
            ]);

            return $address->refresh();
        });
    }

    public function delete(Address $address): void
    {
        DB::transaction(function () use ($address) {
            $user = $address->user;
            $wasDefault = $address->is_default;

            $address->delete();

            if ($wasDefault) {
                $fallback = $user->addresses()->oldest('id')->first();
                if ($fallback) {
                    $fallback->update(['is_default' => true]);
                }
            }
        });
    }

    public function setDefault(Address $address): Address
    {
        return DB::transaction(function () use ($address) {
            $address->user->addresses()->update(['is_default' => false]);
            $address->update(['is_default' => true]);

            return $address->refresh();
        });
    }
}

