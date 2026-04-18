<?php

use App\Services\CartService;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component {
    public bool $isOpening = false;

    #[On('show-cart-modal')]
    public function onShowCartModal(): void
    {
        $this->isOpening = true;
        $this->dispatch('show-cart-modal-js');
    }

    #[On('cart-updated')]
    public function refreshCart(): void
    {
        // Listener triggers a re-render, computed props will refresh.
    }

    public function finishOpening(): void
    {
        $this->isOpening = false;
    }

    public function removeLine(string $key): void
    {
        if ($this->cartService()->remove($key)) {
            $this->dispatch('cart-updated');
        }
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

    #[Computed]
    public function cartLines(): Collection
    {
        return $this->cartService()->lines();
    }

    #[Computed]
    public function cartTotal(): float
    {
        return $this->cartService()->total();
    }

    #[Computed]
    public function cartCount(): int
    {
        return $this->cartService()->count();
    }

    protected function cartService(): CartService
    {
        return app(CartService::class);
    }
};
?>

<div>
    @php
        $cartLines = $this->cartLines;
        $cartTotal = $this->cartTotal;
    @endphp

    <style>
        .snm-cart-line {
            border-bottom: 1px dashed #e9ecef;
            padding-bottom: 12px;
            margin-bottom: 12px;
        }
        .snm-cart-line:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }
    </style>

    @if ($isOpening)
        <div class="py-5 d-flex flex-column align-items-center justify-content-center text-center" wire:init="finishOpening">
            <div class="spinner-border text-primary mb-3" role="status" aria-hidden="true"></div>
            <p class="text-muted mb-0">Loading your cart...</p>
        </div>
    @else
        <h6 class="fw-semibold mb-3 d-flex justify-content-between align-items-center">
            <span>Your Bag</span>
            <span class="badge text-bg-secondary">{{ $cartLines->count() }} item(s)</span>
        </h6>

        @if ($cartLines->isEmpty())
            <div class="text-center py-4">
                <i class="bi bi-bag-x fs-1 text-muted mb-3"></i>
                <p class="text-muted mb-0">Your bag is currently empty.</p>
                <p class="text-muted small">Add some delicious items to get started!</p>
            </div>
        @else
            <div class="cart-items" style="max-height: 400px; overflow-y: auto;">
                @foreach ($cartLines as $line)
                    <div class="snm-cart-line">
                        <div class="d-flex justify-content-between align-items-start gap-3">
                            <div class="flex-grow-1">
                                <div class="fw-semibold">{{ $line['name'] }} x {{ $line['quantity'] }}</div>
                                @if (!empty($line['addons']))
                                    <div class="small text-muted">
                                        Add-ons: {{ collect($line['addons'])->pluck('name')->join(', ') }}
                                    </div>
                                @endif
                                @if (!empty($line['note']))
                                    <div class="small text-muted">
                                        Note: {{ $line['note'] }}
                                    </div>
                                @endif
                            </div>
                            <div class="text-end">
                                <div class="fw-semibold">&#8377;{{ number_format((float) $line['line_total'], 2) }}</div>
                                <button type="button" class="btn btn-sm btn-link text-danger p-0 mt-1" wire:click="removeLine('{{ $line['key'] }}')">
                                    <i class="bi bi-trash"></i> Remove
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <hr class="my-3">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="fw-semibold">Bag Total</span>
                <span class="fw-bold fs-5">&#8377;{{ number_format($cartTotal, 2) }}</span>
            </div>

            <div class="d-grid gap-2">
                <button type="button" class="btn btn-success w-100" wire:click="proceedToCheckout">
                    <i class="bi bi-credit-card me-2"></i>Proceed to Checkout
                </button>
                <button type="button" class="btn btn-outline-secondary w-100" data-bs-dismiss="modal">
                    <i class="bi bi-arrow-left me-2"></i>Continue Shopping
                </button>
            </div>
        @endif
    @endif
</div>
