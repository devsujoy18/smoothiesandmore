<div>
    @php
        $product = $this->product;
        $cartLines = $this->cartLines;
        $cartCount = $this->cartCount;
        $cartTotal = $this->cartTotal;
    @endphp

    <style>
        .snm-addon-card {
            border: 1px solid #e9ecef;
            border-radius: 12px;
            padding: 10px 12px;
            transition: border-color .2s ease, box-shadow .2s ease;
        }
        .snm-addon-card:hover {
            border-color: #ced4da;
            box-shadow: 0 3px 10px rgba(17, 24, 39, .08);
        }
        .snm-stepper {
            border: 1px solid #dee2e6;
            border-radius: 999px;
            overflow: hidden;
        }
        .snm-stepper button {
            min-width: 36px;
        }
        .snm-bag-preview {
            border: 1px solid var(--glass-border);
            border-radius: 18px;
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.09) 0%, rgba(255, 255, 255, 0.04) 100%);
            backdrop-filter: blur(16px) saturate(180%);
            box-shadow: 0 18px 35px rgba(0, 0, 0, 0.22);
            padding: 18px;
        }
        .snm-bag-preview .text-muted {
            color: rgba(255, 255, 255, 0.7) !important;
        }
        .snm-bag-line {
            border-top: 1px dashed rgba(255, 255, 255, 0.14);
            padding-top: 12px;
            margin-top: 12px;
        }
        .snm-bag-line:first-child {
            border-top: none;
            padding-top: 0;
            margin-top: 0;
        }
        .snm-bag-empty {
            border: 1px dashed rgba(255, 255, 255, 0.16);
            border-radius: 14px;
            padding: 18px;
            background: rgba(255, 255, 255, 0.03);
        }
        .snm-status-banner {
            border-radius: 14px;
            border: 1px solid rgba(60, 198, 132, 0.35);
            background: linear-gradient(180deg, rgba(29, 109, 77, 0.45) 0%, rgba(16, 54, 39, 0.55) 100%);
            color: #e7fff3;
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.18);
        }
        .snm-status-banner .small {
            color: rgba(231, 255, 243, 0.8) !important;
        }
        .snm-bag-pill {
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #fff;
        }
        .snm-remove-link {
            color: #ff8ea1 !important;
        }
        .snm-remove-link:hover {
            color: #ffb8c4 !important;
        }
    </style>

    @if ($errors->has('product'))
        <div class="alert alert-warning py-2">{{ $errors->first('product') }}</div>
    @endif

    @if ($isOpening)
        <div class="py-5 d-flex flex-column align-items-center justify-content-center text-center" wire:init="finishOpening">
            <div class="spinner-border text-primary mb-3" role="status" aria-hidden="true"></div>
            <p class="text-muted mb-0">Preparing your order...</p>
        </div>
    @elseif ($product)
        <div class="row g-3">
            <div class="col-md-4">
                @if ($product->image)
                    <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="img-fluid rounded-3 border">
                @else
                    <div class="bg-light rounded-3 border d-flex align-items-center justify-content-center h-100 text-muted">
                        No image
                    </div>
                @endif
            </div>

            <div class="col-md-8">
                <h5 class="mb-1">{{ $product->name }}</h5>
                @if ($product->description)
                    <p class="text-muted small mb-2">{{ $product->description }}</p>
                @endif
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge text-bg-dark">Base Price</span>
                    <span class="fw-semibold">&#8377;{{ number_format($product->price, 2) }}</span>
                </div>
            </div>
        </div>

        <hr class="my-3">

        @if ($errors->has('selectedAddons'))
            <div class="alert alert-danger py-2">{{ $errors->first('selectedAddons') }}</div>
        @endif

        @if ($product->addons->count())
            <h6 class="fw-semibold mb-2">Customize Add-ons</h6>
            <div class="d-grid gap-2 mb-3">
                @foreach ($product->addons as $addon)
                    <label class="snm-addon-card d-flex align-items-start justify-content-between gap-3">
                        <div class="form-check m-0">
                            <input
                                class="form-check-input"
                                type="checkbox"
                                value="{{ $addon->id }}"
                                wire:model.live="selectedAddons"
                                id="addon_{{ $addon->id }}"
                            >
                            <span class="form-check-label ms-1" for="addon_{{ $addon->id }}">
                                {{ $addon->name }}
                                @if ($addon->pivot->is_required)
                                    <span class="badge text-bg-danger ms-1">Required</span>
                                @endif
                            </span>
                        </div>
                        <span class="fw-semibold text-nowrap">&#8377;{{ number_format($addon->price, 2) }}</span>
                    </label>
                @endforeach
            </div>
        @endif

        <div class="mb-3">
            <label for="snm_note" class="form-label fw-semibold mb-1">Special Note (Optional)</label>
            <textarea id="snm_note" rows="2" class="form-control" wire:model.live.debounce.300ms="note" placeholder="Less sugar, no ice, etc."></textarea>
        </div>

                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
                <div class="small text-muted mb-1">Quantity</div>
                <div class="snm-stepper d-inline-flex">
                    <button type="button" class="btn btn-light border-0" wire:click="decreaseQuantity">-</button>
                    <span class="px-3 d-flex align-items-center fw-semibold">{{ $quantity }}</span>
                    <button type="button" class="btn btn-light border-0" wire:click="increaseQuantity">+</button>
                </div>
            </div>
            <button type="button" class="btn btn-primary px-4" wire:click="addToCart" wire:loading.attr="disabled">
                <span wire:loading.remove wire:target="addToCart">Add to Bag</span>
                <span wire:loading wire:target="addToCart">
                    <span class="spinner-border spinner-border-sm me-1"></span>
                    Adding...
                </span>
            </button>
        </div>

        @if ($statusMessage)
            <div class="alert alert-success snm-status-banner mt-3 mb-0 d-flex align-items-start gap-2">
                <i class="bi bi-check-circle-fill mt-1"></i>
                <div>
                    <div class="fw-semibold">{{ $statusMessage }}</div>
                    <div class="small text-muted">Your bag preview updates instantly below.</div>
                </div>
            </div>
        @endif

        <div class="snm-bag-preview mt-4">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
                <div>
                    <h6 class="fw-semibold mb-1">Your Bag</h6>
                    <p class="text-muted small mb-0">Review items you have already added before placing the order.</p>
                </div>
                <div class="text-end">
                    <div class="badge snm-bag-pill rounded-pill px-3 py-2">{{ $cartCount }} item(s)</div>
                    <div class="fw-semibold mt-2">&#8377;{{ number_format($cartTotal, 2) }}</div>
                </div>
            </div>

            @if ($cartLines->isEmpty())
                <div class="snm-bag-empty text-center">
                    <i class="bi bi-bag fs-2 text-muted"></i>
                    <div class="fw-semibold mt-2">Your bag is empty</div>
                    <div class="text-muted small">Add this item or customize another one to start your order.</div>
                </div>
            @else
                @foreach ($cartLines as $line)
                    <div class="snm-bag-line d-flex justify-content-between align-items-start gap-3">
                        <div class="flex-grow-1">
                            <div class="fw-semibold">{{ $line['name'] }} x {{ $line['quantity'] }}</div>
                            @if (! empty($line['addons']))
                                <div class="small text-muted">
                                    Add-ons: {{ collect($line['addons'])->pluck('name')->join(', ') }}
                                </div>
                            @endif
                            @if (! empty($line['note']))
                                <div class="small text-muted">
                                    Note: {{ $line['note'] }}
                                </div>
                            @endif
                        </div>
                        <div class="text-end">
                            <div class="fw-semibold">&#8377;{{ number_format((float) $line['line_total'], 2) }}</div>
                            <button
                                type="button"
                                class="btn btn-link btn-sm snm-remove-link text-decoration-none p-0 mt-1"
                                wire:click="removeBagLine('{{ $line['key'] }}')"
                                wire:loading.attr="disabled"
                            >
                                Remove
                            </button>
                        </div>
                    </div>
                @endforeach

                <div class="d-flex justify-content-between align-items-center pt-3 mt-3 border-top">
                    <span class="fw-semibold">Bag Total</span>
                    <span class="fw-bold fs-5">&#8377;{{ number_format($cartTotal, 2) }}</span>

                    <button type="button" class="btn btn-success w-100" wire:click="proceedToCheckout">
                        <i class="bi bi-credit-card me-2"></i>Proceed to Checkout
                    </button>
                </div>
            @endif
        </div>
    @else
        <div class="alert alert-info mb-0">
            Select a menu item to customize and add it to your bag.
        </div>
    @endif
</div>
