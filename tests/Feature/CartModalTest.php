<?php

use App\Models\User;
use App\Services\CartService;
use Livewire\Livewire;

test('cart modal shows the empty bag state when session cart is empty', function () {
    Livewire::test('cart-modal')
        ->assertSee('Your bag is currently empty.');
});

test('cart modal shows loader before rendering cart items', function () {
    session()->put(CartService::SESSION_KEY, [
        '1|11' => [
            'key' => '1|11',
            'product_id' => 1,
            'name' => 'Protein Smoothie',
            'quantity' => 2,
            'line_total' => 340.00,
            'note' => 'Less sugar',
            'addons' => [
                ['id' => 11, 'name' => 'Protein', 'price' => 20.00],
            ],
        ],
    ]);

    Livewire::test('cart-modal')
        ->dispatch('show-cart-modal')
        ->assertSet('isOpening', true)
        ->assertDispatched('show-cart-modal-js')
        ->assertSee('Loading your cart...')
        ->call('finishOpening')
        ->assertSet('isOpening', false)
        ->assertSee('Protein Smoothie x 2');
});

test('cart modal renders cart items from the session', function () {
    session()->put(CartService::SESSION_KEY, [
        '1|11' => [
            'key' => '1|11',
            'product_id' => 1,
            'name' => 'Protein Smoothie',
            'quantity' => 2,
            'line_total' => 340.00,
            'note' => 'Less sugar',
            'addons' => [
                ['id' => 11, 'name' => 'Protein', 'price' => 20.00],
            ],
        ],
    ]);

    Livewire::test('cart-modal')
        ->assertSee('Protein Smoothie x 2')
        ->assertSee('Add-ons: Protein')
        ->assertSee('Note: Less sugar')
        ->assertSee('340.00');
});

test('cart modal removes an item from the session cart', function () {
    session()->put(CartService::SESSION_KEY, [
        '1|11' => [
            'key' => '1|11',
            'product_id' => 1,
            'name' => 'Protein Smoothie',
            'quantity' => 1,
            'line_total' => 170.00,
            'note' => '',
            'addons' => [],
        ],
    ]);

    Livewire::test('cart-modal')
        ->call('removeLine', '1|11')
        ->assertDispatched('cart-updated')
        ->assertSee('Your bag is currently empty.');

    expect(session()->get(CartService::SESSION_KEY, []))->toBe([]);
});

test('cart modal opens checkout auth for guests with cart items', function () {
    session()->put(CartService::SESSION_KEY, [
        '1|' => [
            'key' => '1|',
            'product_id' => 1,
            'name' => 'Classic Smoothie',
            'quantity' => 1,
            'line_total' => 150.00,
            'note' => '',
            'addons' => [],
        ],
    ]);

    Livewire::test('cart-modal')
        ->call('proceedToCheckout')
        ->assertDispatched('open-checkout-auth-modal');
});

test('cart modal redirects authenticated users to checkout addresses', function () {
    session()->put(CartService::SESSION_KEY, [
        '1|' => [
            'key' => '1|',
            'product_id' => 1,
            'name' => 'Classic Smoothie',
            'quantity' => 1,
            'line_total' => 150.00,
            'note' => '',
            'addons' => [],
        ],
    ]);

    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test('cart-modal')
        ->call('proceedToCheckout')
        ->assertRedirect(route('checkout.addresses.index'));
});
