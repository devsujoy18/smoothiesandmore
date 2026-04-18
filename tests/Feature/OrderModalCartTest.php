<?php

use App\Livewire\OrderModalCart;
use App\Models\AddOn;
use App\Models\Category;
use App\Models\Product;
use App\Services\CartService;
use Livewire\Livewire;

function createOrderModalProduct(bool $withAddon = false): Product
{
    $category = Category::create([
        'name' => fake()->unique()->words(2, true),
        'sort_no' => 1,
    ]);

    $product = Product::create([
        'name' => fake()->unique()->words(3, true),
        'description' => 'Freshly blended for testing.',
        'price' => 150,
        'mrp' => 180,
        'category_id' => $category->id,
        'image' => null,
        'is_available' => true,
        'sort_no' => 1,
    ]);

    if ($withAddon) {
        $addon = AddOn::create([
            'name' => fake()->unique()->words(2, true),
            'price' => 20,
            'image' => null,
            'is_active' => true,
        ]);

        $product->addons()->attach($addon->id, [
            'is_required' => false,
            'min_selection' => 0,
            'max_selection' => 1,
        ]);
    }

    return $product->fresh('addons');
}

test('order modal shows success feedback and bag preview after adding an item', function () {
    $product = createOrderModalProduct(withAddon: true);
    $addon = $product->addons->first();

    Livewire::test(OrderModalCart::class)
        ->set('productId', $product->id)
        ->set('quantity', 2)
        ->set('note', 'Less sugar')
        ->set('selectedAddons', [$addon->id])
        ->call('addToCart')
        ->assertDispatched('cart-updated')
        ->assertSee('added to your bag')
        ->assertSee($product->name . ' x 2')
        ->assertSee('Add-ons: ' . $addon->name)
        ->assertSee('Note: Less sugar');

    expect(session()->get(CartService::SESSION_KEY))->not->toBeEmpty();
});

test('order modal can remove an item from the embedded bag preview', function () {
    $product = createOrderModalProduct();

    Livewire::test(OrderModalCart::class)
        ->set('productId', $product->id)
        ->call('addToCart')
        ->assertSee($product->name . ' x 1')
        ->call('removeBagLine', $product->id . '|')
        ->assertDispatched('cart-updated')
        ->assertSee('Your bag is empty');

    expect(session()->get(CartService::SESSION_KEY, []))->toBe([]);
});
