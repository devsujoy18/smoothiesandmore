<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Collection;

class CartService
{
    public const SESSION_KEY = 'snm_cart';

    public function all(): array
    {
        return session()->get(self::SESSION_KEY, []);
    }

    public function lines(): Collection
    {
        return collect($this->all());
    }

    public function put(array $cart): void
    {
        session()->put(self::SESSION_KEY, $cart);
    }

    public function clear(): void
    {
        session()->forget(self::SESSION_KEY);
    }

    public function count(): int
    {
        return (int) $this->lines()->sum('quantity');
    }

    public function total(): float
    {
        return (float) $this->lines()->sum('line_total');
    }

    public function isEmpty(): bool
    {
        return $this->count() < 1;
    }

    public function remove(string $key): bool
    {
        $cart = $this->all();

        if (! isset($cart[$key])) {
            return false;
        }

        unset($cart[$key]);
        $this->put($cart);

        return true;
    }

    public function addProduct(Product $product, Collection $selectedAddons, int $quantity, string $note = ''): string
    {
        $selectedAddonIds = $selectedAddons->pluck('id')->map(fn ($id) => (int) $id)->values()->all();
        $lineKey = $this->buildLineKey((int) $product->id, $selectedAddonIds);

        $cart = $this->all();

        if (isset($cart[$lineKey])) {
            $cart[$lineKey]['quantity'] += $quantity;
            $cart[$lineKey]['line_total'] = round($cart[$lineKey]['unit_price'] * $cart[$lineKey]['quantity'], 2);
            $this->put($cart);

            return $lineKey;
        }

        $addonsTotal = $selectedAddons->sum(fn ($addon) => (float) $addon->price);
        $unitPrice = (float) $product->price + $addonsTotal;

        $cart[$lineKey] = [
            'key' => $lineKey,
            'product_id' => (int) $product->id,
            'name' => (string) $product->name,
            'image' => $product->image,
            'quantity' => $quantity,
            'base_price' => (float) $product->price,
            'unit_price' => round($unitPrice, 2),
            'line_total' => round($unitPrice * $quantity, 2),
            'note' => trim($note),
            'addons' => $selectedAddons->map(fn ($addon) => [
                'id' => (int) $addon->id,
                'name' => (string) $addon->name,
                'price' => (float) $addon->price,
                'required' => (bool) ($addon->pivot->is_required ?? false),
            ])->values()->all(),
        ];

        $this->put($cart);

        return $lineKey;
    }

    public function buildLineKey(int $productId, array $addonIds): string
    {
        $signature = collect($addonIds)->map(fn ($id) => (int) $id)->sort()->values()->implode('-');

        return $productId . '|' . $signature;
    }
}
