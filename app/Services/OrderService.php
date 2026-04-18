<?php

namespace App\Services;

use App\Events\OrderPlaced;
use App\Models\Address;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function placeFromCart(User $user, Address $address, array $cartLines): Order
    {
        return DB::transaction(function () use ($user, $address, $cartLines) {
            $subtotal = (float) collect($cartLines)->sum('line_total');
            $taxAmount = 0.0;
            $discountAmount = 0.0;
            $totalAmount = $subtotal + $taxAmount - $discountAmount;

            $order = Order::create([
                'user_id' => $user->id,
                'order_number' => $this->generateOrderNumber(),
                'address_snapshot' => [
                    'full_name' => $address->full_name,
                    'phone' => $address->phone,
                    'address_line_1' => $address->address_line_1,
                    'address_line_2' => $address->address_line_2,
                    'city' => $address->city,
                    'state' => $address->state,
                    'postal_code' => $address->postal_code,
                    'country' => $address->country,
                ],
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'discount_amount' => $discountAmount,
                'total_amount' => $totalAmount,
                'currency' => 'inr',
                'payment_status' => Order::PAYMENT_PENDING,
                'order_status' => Order::STATUS_PENDING,
                'placed_at' => now(),
            ]);

            foreach ($cartLines as $line) {
                $mainQuantity = (int) ($line['quantity'] ?? 1);
                $addons = collect($line['addons'] ?? []);
                $addonsUnitTotal = (float) $addons->sum('price');
                $mainUnitPrice = max(0, ((float) ($line['unit_price'] ?? 0) - $addonsUnitTotal));

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $line['product_id'] ?? null,
                    'addon_product_id' => null,
                    'item_type' => OrderItem::TYPE_MAIN,
                    'product_name_snapshot' => (string) ($line['name'] ?? 'Item'),
                    'price_snapshot' => $mainUnitPrice,
                    'quantity' => $mainQuantity,
                    'total_price' => round($mainUnitPrice * $mainQuantity, 2),
                ]);

                foreach ($addons as $addon) {
                    $addonPrice = (float) ($addon['price'] ?? 0);

                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $line['product_id'] ?? null,
                        'addon_product_id' => $addon['id'] ?? null,
                        'item_type' => OrderItem::TYPE_ADDON,
                        'product_name_snapshot' => (string) ($addon['name'] ?? 'Add-on'),
                        'price_snapshot' => $addonPrice,
                        'quantity' => $mainQuantity,
                        'total_price' => round($addonPrice * $mainQuantity, 2),
                    ]);
                }
            }

            event(new OrderPlaced($order));

            return $order;
        });
    }

    private function generateOrderNumber(): string
    {
        do {
            $orderNumber = 'SNM-' . now()->format('Ymd') . '-' . strtoupper(substr(bin2hex(random_bytes(3)), 0, 6));
        } while (Order::query()->where('order_number', $orderNumber)->exists());

        return $orderNumber;
    }
}

