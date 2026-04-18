<?php

namespace App\Livewire;

use App\Models\Product;
use App\Services\CartService;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class OrderModalCart extends Component
{
    public ?int $productId = null;

    public int $quantity = 1;

    public array $selectedAddons = [];

    public string $note = '';

    public bool $isOpening = false;

    public ?string $statusMessage = null;

    #[On('open-order-modal')]
    public function onOpenOrderModal(?int $productId = null): void
    {
        $this->isOpening = true;
        $this->productId = $productId ? (int) $productId : null;
        $this->quantity = 1;
        $this->selectedAddons = [];
        $this->note = '';
        $this->statusMessage = null;
        $this->resetValidation();

        // Open modal only after this component state is updated.
        $this->dispatch('show-order-modal');
    }

    public function finishOpening(): void
    {
        $this->isOpening = false;
    }

    public function decreaseQuantity(): void
    {
        if ($this->quantity > 1) {
            $this->quantity--;
        }
    }

    public function increaseQuantity(): void
    {
        $this->quantity++;
    }

    public function addToCart(): void
    {
        $this->statusMessage = null;
        $product = $this->product;

        if (! $product) {
            $this->addError('product', 'Please select an item before adding to bag.');

            return;
        }

        if ($this->quantity < 1) {
            $this->addError('quantity', 'Quantity must be at least 1.');

            return;
        }

        $addons = $product->addons;
        $addonIds = $addons->pluck('id');
        $selectedIds = collect($this->selectedAddons)
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values();

        if ($selectedIds->diff($addonIds)->isNotEmpty()) {
            $this->addError('selectedAddons', 'Invalid add-on selection. Please try again.');

            return;
        }

        $requiredIds = $addons
            ->filter(fn ($addon) => (bool) $addon->pivot->is_required)
            ->pluck('id');

        if ($requiredIds->diff($selectedIds)->isNotEmpty()) {
            $this->addError('selectedAddons', 'Please select all required add-ons.');

            return;
        }

        $selectedAddons = $addons->whereIn('id', $selectedIds)->values();
        $this->cartService()->addProduct($product, $selectedAddons, $this->quantity, $this->note);
        $bagCount = $this->cartService()->count();
        $bagTotal = $this->cartService()->total();

        $this->dispatch('cart-updated');
        $this->statusMessage = sprintf(
            '%s added to your bag. %d item(s) in bag - Rs. %s total.',
            $product->name,
            $bagCount,
            number_format($bagTotal, 2)
        );

        $this->quantity = 1;
        $this->selectedAddons = [];
        $this->note = '';
        $this->resetValidation();
    }

    public function removeBagLine(string $key): void
    {
        if (! $this->cartService()->remove($key)) {
            return;
        }

        $this->dispatch('cart-updated');

        if ($this->cartService()->isEmpty()) {
            $this->statusMessage = 'Your bag is now empty.';

            return;
        }

        $this->statusMessage = sprintf(
            'Item removed. %d item(s) remain in your bag - Rs. %s total.',
            $this->cartService()->count(),
            number_format($this->cartService()->total(), 2)
        );
    }

    #[Computed]
    public function product(): ?Product
    {
        if (! $this->productId) {
            return null;
        }

        return Product::query()
            ->with(['addons' => fn ($query) => $query->where('is_active', true)->orderBy('name')])
            ->find($this->productId);
    }

    #[Computed]
    public function cartLines(): Collection
    {
        return $this->cartService()->lines();
    }

    #[Computed]
    public function cartCount(): int
    {
        return $this->cartService()->count();
    }

    #[Computed]
    public function cartTotal(): float
    {
        return $this->cartService()->total();
    }

    protected function cartService(): CartService
    {
        return app(CartService::class);
    }

    public function render()
    {
        return view('livewire.order-modal-cart');
    }

    public function proceedToCheckout(): void
    {
        if ($this->cartService()->isEmpty()) {
            return;
        }

        if (auth()->check()) {
            $this->redirectRoute('checkout.addresses.index', navigate: true);

            return;
        }

        $this->dispatch('open-checkout-auth-modal');
    }
}
