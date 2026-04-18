<?php

namespace App\Livewire;

use App\Services\CartService;
use Livewire\Attributes\On;
use Livewire\Component;

class CartBagIcon extends Component
{
    public int $count = 0;

    public function mount(): void
    {
        $this->refreshCount();
    }

    #[On('cart-updated')]
    public function refreshCount(): void
    {
        $this->count = $this->cartService()->count();
    }

    public function openBag(): void
    {
        $this->dispatch('show-cart-modal');
    }

    public function render()
    {
        return view('livewire.cart-bag-icon');
    }

    protected function cartService(): CartService
    {
        return app(CartService::class);
    }
}
