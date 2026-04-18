<?php

namespace App\Http\Controllers;

use App\Http\Requests\Checkout\PlaceOrderRequest;
use App\Models\Address;
use App\Models\Order;
use App\Services\CartService;
use App\Services\OrderService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CheckoutOrderController extends Controller
{
    public function store(PlaceOrderRequest $request, CartService $cartService, OrderService $orderService): RedirectResponse
    {
        $address = Address::query()
            ->where('user_id', $request->user()->id)
            ->findOrFail((int) $request->input('address_id'));

        $order = $orderService->placeFromCart(
            user: $request->user(),
            address: $address,
            cartLines: $cartService->all(),
        );

        $cartService->clear();

        return redirect()
            ->route('checkout.orders.success', $order);
    }

    public function success(Order $order): View
    {
        abort_unless($order->user_id === auth()->id(), 403);

        $order->load('items');

        return view('checkout.order-success', [
            'order' => $order,
            'mainItems' => $order->items->where('item_type', 'main')->values(),
            'addonItems' => $order->items->where('item_type', 'addon')->values(),
        ]);
    }
}
