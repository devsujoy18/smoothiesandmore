<x-front-layout>
    <style>
        .order-success-page .card {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border) !important;
            backdrop-filter: blur(12px) saturate(180%);
            border-radius: var(--border-radius-md);
            color: var(--text-dark);
        }

        .order-success-page .muted {
            color: var(--text-muted);
        }

        .order-success-page .status-chip {
            border-radius: 999px;
            font-size: 12px;
            padding: 6px 12px;
            font-weight: 600;
        }

        .order-success-page .row-line {
            border-bottom: 1px dashed rgba(255, 255, 255, 0.15);
            padding: 12px 0;
        }

        .order-success-page .row-line:last-child {
            border-bottom: none;
        }
    </style>

    <section class="py-5 order-success-page" style="min-height: 70vh;">
        <div class="container">
            <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
                <div>
                    <h2 class="mb-1">Order Placed Successfully</h2>
                    <p class="muted mb-0">Thank you. Your order is confirmed in our system.</p>
                </div>
                <a href="{{ route('home') }}" class="btn btn-outline-dark">Back to Home</a>
            </div>

            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="card border-0">
                        <div class="card-body p-4">
                            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
                                <div>
                                    <div class="small muted">Order Number</div>
                                    <div class="fw-bold fs-5">{{ $order->order_number }}</div>
                                </div>
                                <div class="text-end">
                                    <span class="status-chip bg-warning text-dark">{{ strtoupper($order->order_status) }}</span>
                                    <div class="small muted mt-2">
                                        {{ optional($order->placed_at)->format('d M Y, h:i A') }}
                                    </div>
                                </div>
                            </div>

                            <h5 class="mb-3">Items</h5>
                            @foreach ($mainItems as $item)
                                <div class="row-line d-flex justify-content-between align-items-start gap-3">
                                    <div>
                                        <div class="fw-semibold">{{ $item->product_name_snapshot }}</div>
                                        <div class="small muted">Qty: {{ $item->quantity }}</div>
                                    </div>
                                    <div class="fw-semibold">&#8377;{{ number_format((float) $item->total_price, 2) }}</div>
                                </div>
                            @endforeach

                            @if ($addonItems->isNotEmpty())
                                <h6 class="mt-3 mb-2">Add-ons</h6>
                                @foreach ($addonItems as $item)
                                    <div class="row-line d-flex justify-content-between align-items-start gap-3">
                                        <div>
                                            <div class="fw-semibold">{{ $item->product_name_snapshot }}</div>
                                            <div class="small muted">Qty: {{ $item->quantity }}</div>
                                        </div>
                                        <div class="fw-semibold">&#8377;{{ number_format((float) $item->total_price, 2) }}</div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card border-0 mb-3">
                        <div class="card-body p-4">
                            <h6 class="mb-3">Delivery Address</h6>
                            <div class="small muted">
                                <div class="fw-semibold text-white">{{ $order->address_snapshot['full_name'] ?? '' }}</div>
                                <div>{{ $order->address_snapshot['phone'] ?? '' }}</div>
                                <div class="mt-2">
                                    {{ $order->address_snapshot['address_line_1'] ?? '' }}
                                    @if(!empty($order->address_snapshot['address_line_2']))
                                        , {{ $order->address_snapshot['address_line_2'] }}
                                    @endif
                                </div>
                                <div>
                                    {{ $order->address_snapshot['city'] ?? '' }},
                                    {{ $order->address_snapshot['state'] ?? '' }},
                                    {{ $order->address_snapshot['postal_code'] ?? '' }}
                                </div>
                                <div>{{ $order->address_snapshot['country'] ?? '' }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="card border-0">
                        <div class="card-body p-4">
                            <h6 class="mb-3">Payment Summary</h6>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="muted">Subtotal</span>
                                <span>&#8377;{{ number_format((float) $order->subtotal, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="muted">Tax</span>
                                <span>&#8377;{{ number_format((float) $order->tax_amount, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="muted">Discount</span>
                                <span>- &#8377;{{ number_format((float) $order->discount_amount, 2) }}</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between fw-bold fs-5">
                                <span>Total</span>
                                <span>&#8377;{{ number_format((float) $order->total_amount, 2) }}</span>
                            </div>
                            <div class="small muted mt-2">Payment: {{ strtoupper($order->payment_status) }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-front-layout>

