<?php

namespace App\Http\Controllers;

use App\Http\Requests\Checkout\StoreAddressRequest;
use App\Http\Requests\Checkout\UpdateAddressRequest;
use App\Models\Address;
use App\Services\AddressService;
use App\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Gate;

class CheckoutAddressController extends Controller
{
    public function index(CartService $cartService): View
    {
        $user = auth()->user();

        return view('checkout.addresses', [
            'addresses' => $user->addresses()->latest()->get(),
            'canAddAddress' => $user->addresses()->count() < 3,
            'cartTotal' => $cartService->total(),
            'cartCount' => $cartService->count(),
        ]);
    }

    public function store(StoreAddressRequest $request, AddressService $addressService): RedirectResponse
    {
        $addressService->create($request->user(), $request->validated());

        return back()->with('message', 'Address added successfully.');
    }

    public function update(UpdateAddressRequest $request, Address $address, AddressService $addressService): RedirectResponse
    {
        //$this->authorize('update', $address);
        Gate::authorize('update-address', $address);

        $addressService->update($address, $request->validated());

        return back()->with('message', 'Address updated successfully.');
    }

    public function destroy(Address $address, AddressService $addressService): RedirectResponse
    {
        //$this->authorize('delete', $address);
        Gate::authorize('delete-address', $address);

        $addressService->delete($address);

        return back()->with('message', 'Address deleted successfully.');
    }

    public function setDefault(Address $address, AddressService $addressService): RedirectResponse
    {
        //$this->authorize('setDefault', $address);
        Gate::authorize('set-default-address', $address);

        $addressService->setDefault($address);

        return back()->with('message', 'Default address updated.');
    }
}

