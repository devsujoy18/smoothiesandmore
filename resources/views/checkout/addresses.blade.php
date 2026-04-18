<x-front-layout>
    <style>
        .checkout-page .card {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border) !important;
            backdrop-filter: blur(12px) saturate(180%);
            border-radius: var(--border-radius-md);
            color: var(--text-dark);
        }

        .checkout-page .card .card-footer {
            background: transparent !important;
        }

        .checkout-page .address-item {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid var(--glass-border) !important;
            transition: border-color 0.2s ease, background 0.2s ease;
        }

        .checkout-page .address-item:hover {
            border-color: rgba(230, 0, 126, 0.45) !important;
            background: rgba(255, 255, 255, 0.06);
        }

        .checkout-page .form-control {
            background: rgba(255, 255, 255, 0.08) !important;
            border-color: rgba(255, 255, 255, 0.2) !important;
            color: #fff !important;
        }

        .checkout-page .form-control::placeholder {
            color: rgba(255, 255, 255, 0.65) !important;
        }

        .checkout-page .form-control:focus {
            border-color: var(--primary-color) !important;
            box-shadow: 0 0 0 0.2rem rgba(230, 0, 126, 0.2) !important;
        }

        .checkout-page .form-check-label,
        .checkout-page .form-label,
        .checkout-page .small,
        .checkout-page .text-muted {
            color: var(--text-muted) !important;
        }

        .checkout-page .btn-outline-secondary,
        .checkout-page .btn-outline-primary,
        .checkout-page .btn-outline-danger {
            border-radius: 999px;
        }
    </style>
    <section class="py-5 checkout-page" style="min-height: 70vh;">
        <div class="container">
            @php
                $selectedAddressId = old('address_id')
                    ? $addresses->firstWhere('id', (int) old('address_id'))?->id
                    : ($addresses->firstWhere('is_default', true)?->id ?? $addresses->first()?->id);
            @endphp

            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
                <div>
                    <h2 class="mb-1">Checkout - Delivery Address</h2>
                    <p class="text-muted mb-0">Select an address and place your order.</p>
                </div>
                <div class="text-end">
                    <div class="small text-muted">Items in bag: {{ $cartCount }}</div>
                    <div class="fw-bold">Total: &#8377;{{ number_format($cartTotal, 2) }}</div>
                </div>
            </div>

            @if (session('message'))
                <div class="alert alert-success">{{ session('message') }}</div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="row g-4">
                <div class="col-lg-7">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title mb-3">Choose Address</h5>

                            @forelse ($addresses as $address)
                                <label class="address-item rounded-3 p-3 d-block mb-3">
                                    <div class="d-flex justify-content-between align-items-start gap-2">
                                        <div>
                                            <input type="radio" name="address_id" value="{{ $address->id }}"
                                                form="placeOrderForm"
                                                class="form-check-input me-2"
                                                @checked($selectedAddressId === $address->id)>
                                            <span class="fw-semibold">{{ $address->full_name }}</span>
                                            @if ($address->is_default)
                                                <span class="badge bg-primary ms-2">Default</span>
                                            @endif
                                            <div class="small text-muted mt-1">
                                                {{ $address->phone }}<br>
                                                {{ $address->address_line_1 }},
                                                @if($address->address_line_2) {{ $address->address_line_2 }}, @endif
                                                {{ $address->city }}, {{ $address->state }}, {{ $address->postal_code }},
                                                {{ $address->country }}
                                            </div>
                                        </div>
                                        <div class="d-flex gap-2 flex-wrap justify-content-end">
                                            <button type="button" class="btn btn-sm btn-outline-secondary"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#editAddress{{ $address->id }}">
                                                Edit
                                            </button>
                                            @if (! $address->is_default)
                                                <form method="POST" action="{{ route('checkout.addresses.default', $address) }}">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-primary">Set Default</button>
                                                </form>
                                            @endif
                                            <form method="POST" action="{{ route('checkout.addresses.destroy', $address) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="collapse mt-3" id="editAddress{{ $address->id }}">
                                        <form method="POST" action="{{ route('checkout.addresses.update', $address) }}">
                                            @csrf
                                            @method('PUT')
                                            <div class="row g-2">
                                                <div class="col-md-6">
                                                    <input type="text" name="full_name" class="form-control" value="{{ $address->full_name }}" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <input type="text" name="phone" class="form-control" value="{{ $address->phone }}" required>
                                                </div>
                                                <div class="col-12">
                                                    <input type="text" name="address_line_1" class="form-control" value="{{ $address->address_line_1 }}" required>
                                                </div>
                                                <div class="col-12">
                                                    <input type="text" name="address_line_2" class="form-control" value="{{ $address->address_line_2 }}">
                                                </div>
                                                <div class="col-md-4">
                                                    <input type="text" name="city" class="form-control" value="{{ $address->city }}" required>
                                                </div>
                                                <div class="col-md-4">
                                                    <input type="text" name="state" class="form-control" value="{{ $address->state }}" required>
                                                </div>
                                                <div class="col-md-4">
                                                    <input type="text" name="postal_code" class="form-control" value="{{ $address->postal_code }}" required>
                                                </div>
                                                <div class="col-md-8">
                                                    <input type="text" name="country" class="form-control" value="{{ $address->country }}" required>
                                                </div>
                                                <div class="col-md-4 d-flex align-items-center">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" value="1" name="is_default" id="default{{ $address->id }}" @checked($address->is_default)>
                                                        <label class="form-check-label" for="default{{ $address->id }}">Default</label>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <button type="submit" class="btn btn-sm btn-dark">Update Address</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </label>
                            @empty
                                <p class="text-muted mb-0">No addresses found. Add one to continue.</p>
                            @endforelse
                        </div>
                        <div class="card-footer bg-white border-0 pt-0 pb-4 px-4">
                            <form method="POST" action="{{ route('checkout.orders.store') }}" id="placeOrderForm">
                                @csrf
                                <button type="submit" class="btn btn-primary px-4" @disabled($addresses->isEmpty())>
                                    Place Order
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title mb-3">Add Address</h5>
                            @if (! $canAddAddress)
                                <div class="alert alert-warning py-2">
                                    Maximum 3 addresses allowed.
                                </div>
                            @endif
                            <form method="POST" action="{{ route('checkout.addresses.store') }}">
                                @csrf
                                <div class="row g-2">
                                    <div class="col-12">
                                        <input type="text" name="full_name" class="form-control" placeholder="Full Name" required>
                                    </div>
                                    <div class="col-12">
                                        <input type="text" name="phone" class="form-control" placeholder="Phone" required>
                                    </div>
                                    <div class="col-12">
                                        <input type="text" name="address_line_1" class="form-control" placeholder="Address Line 1" required>
                                    </div>
                                    <div class="col-12">
                                        <input type="text" name="address_line_2" class="form-control" placeholder="Address Line 2 (Optional)">
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" name="city" class="form-control" placeholder="City" required>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" name="state" class="form-control" placeholder="State" required>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" name="postal_code" class="form-control" placeholder="Postal Code" required>
                                    </div>
                                    <div class="col-12">
                                        <input type="text" name="country" class="form-control" value="India" required>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="1" name="is_default" id="isDefault">
                                            <label class="form-check-label" for="isDefault">Set as default address</label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-dark w-100" @disabled(! $canAddAddress)>
                                            Save Address
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-front-layout>
