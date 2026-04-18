<?php

use Livewire\Component;

new class extends Component
{
    public $product;
    public $expanded = false;

    public function mount($product)
    {
        $this->product = $product;
    }

    public function toggleDescription()
    {
        $this->expanded = ! $this->expanded;
    }

    public function openOrderModal($productId)
    {
        $this->dispatch('open-order-modal', productId: (int) $productId);
    }
};
?>
<div class="menu-card h-100">
    <style>
        .menu-desc {
            font-size: 14px;
            color: #cfcfcf;
            line-height: 1.6;
            position: relative;
        }

        .desc-toggle {
            display: inline-block;
            margin-top: 6px;
            font-size: 12px;
            color: #ff4db8;
            cursor: pointer;
            transition: 0.3s ease;
        }

        .desc-toggle:hover {
            color: #ff80d5;
            transform: translateY(-1px);
        }
    </style>
    @if ($product->image)
        <img src="{{ asset($product->image) }}" class="menu-img" alt="{{ $product->name }}">
    @else
        <span class="text-xs text-gray-500">No image</span>
    @endif

    <div class="menu-title">{{ $product->name }}</div>
    @php
        $limit = 120;
        $isExpandable = strlen($product->description) > $limit;
    @endphp

    <div class="menu-desc position-relative">
        <div class="description-text">
            @if($expanded || !$isExpandable)
                {{ $product->description }}
            @else
                {{ \Illuminate\Support\Str::limit($product->description, $limit) }}
            @endif

            @if($isExpandable)
                <span class="desc-toggle" wire:click="toggleDescription">
                    @if($expanded)
                        &#9650;
                    @else
                        &#9660;
                    @endif
                </span>
            @endif
        </div>
    </div>

    <div class="menu-footer mt-auto">
        <span class="price">&#8377;{{ number_format($product->price, 2) }}</span>
        <button
            class="btn-add position-relative"
            wire:click="openOrderModal({{ $product->id }})"
            wire:loading.attr="disabled"
            wire:target="openOrderModal({{ $product->id }})"
        >
            <span wire:loading.remove wire:target="openOrderModal({{ $product->id }})">
                Order Now
            </span>
            <span wire:loading wire:target="openOrderModal({{ $product->id }})">
                <span class="spinner-border spinner-border-sm me-1"></span>
                Loading...
            </span>
        </button>
    </div>
</div>
