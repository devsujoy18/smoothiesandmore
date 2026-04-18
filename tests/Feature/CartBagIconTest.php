<?php

use App\Livewire\CartBagIcon;
use Livewire\Livewire;

test('cart bag icon dispatches the cart modal event', function () {
    Livewire::test(CartBagIcon::class)
        ->call('openBag')
        ->assertDispatched('show-cart-modal');
});
