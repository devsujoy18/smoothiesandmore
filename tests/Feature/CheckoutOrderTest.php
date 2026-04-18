<?php

use App\Models\AddOn;
use App\Models\Address;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Services\CartService;

function createCheckoutCartLine(bool $withAddon = false): array
{
    $category = Category::create([
        'name' => fake()->unique()->words(2, true),
        'sort_no' => 1,
    ]);

    $product = Product::create([
        'name' => fake()->unique()->words(3, true),
        'description' => 'Freshly made for checkout testing.',
        'price' => 100,
        'mrp' => 120,
        'category_id' => $category->id,
        'image' => null,
        'is_available' => true,
        'sort_no' => 1,
    ]);

    $addons = [];

    if ($withAddon) {
        $addon = AddOn::create([
            'name' => fake()->unique()->words(2, true),
            'price' => 20,
            'image' => null,
            'is_active' => true,
        ]);

        $addons[] = [
            'id' => $addon->id,
            'name' => $addon->name,
            'price' => 20.0,
            'required' => false,
        ];
    }

    return [
        'key' => $product->id.'|',
        'product_id' => $product->id,
        'name' => $product->name,
        'image' => null,
        'quantity' => 2,
        'base_price' => 100.0,
        'unit_price' => $withAddon ? 120.0 : 100.0,
        'line_total' => $withAddon ? 240.0 : 200.0,
        'note' => 'Less sugar',
        'addons' => $addons,
    ];
}

function createCheckoutAddress(User $user, bool $isDefault = true): Address
{
    return Address::create([
        'user_id' => $user->id,
        'full_name' => fake()->name(),
        'phone' => '9999999999',
        'address_line_1' => '12 Test Street',
        'address_line_2' => 'Near Market',
        'city' => 'Kolkata',
        'state' => 'West Bengal',
        'postal_code' => '700001',
        'country' => 'India',
        'is_default' => $isDefault,
    ]);
}

test('checkout address page binds address radios directly to the place order form', function () {
    $user = User::factory()->create();
    $address = createCheckoutAddress($user);

    $response = $this
        ->actingAs($user)
        ->withSession([
            CartService::SESSION_KEY => [
                'cart-line' => createCheckoutCartLine(),
            ],
        ])
        ->get(route('checkout.addresses.index'));

    $response
        ->assertOk()
        ->assertSee('id="placeOrderForm"', escape: false)
        ->assertSee('name="address_id"', escape: false)
        ->assertSee('form="placeOrderForm"', escape: false)
        ->assertSee('value="'.$address->id.'"', escape: false);

    expect($response->getContent())->not->toContain('id="selectedAddressId"');
});

test('authenticated user can place an order from checkout and the cart is cleared', function () {
    $user = User::factory()->create();
    $address = createCheckoutAddress($user);
    $cartLine = createCheckoutCartLine(withAddon: true);

    $response = $this
        ->actingAs($user)
        ->withSession([
            CartService::SESSION_KEY => [
                $cartLine['key'] => $cartLine,
            ],
        ])
        ->post(route('checkout.orders.store'), [
            'address_id' => $address->id,
        ]);

    $order = Order::query()->with('items')->sole();

    $response->assertRedirect(route('checkout.orders.success', $order));

    $this->assertDatabaseHas('orders', [
        'id' => $order->id,
        'user_id' => $user->id,
        'order_status' => Order::STATUS_PENDING,
        'payment_status' => Order::PAYMENT_PENDING,
    ]);

    expect($order->items)->toHaveCount(2);
    expect($order->total_amount)->toBe('240.00');
    expect(session(CartService::SESSION_KEY))->toBeNull();

    $this
        ->actingAs($user)
        ->get(route('checkout.orders.success', $order))
        ->assertOk()
        ->assertSee($order->order_number)
        ->assertSee('Order Placed Successfully');
});
