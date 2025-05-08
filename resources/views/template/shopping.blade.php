@extends('template.layouts.app')

@section('title', 'Shopping Cart - Yummy')

@section('content')
@extends('template.layouts.session')

<div class="w-full h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-md px-4 py-6">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-semibold text-gray-900">Shopping Cart</h2>
            <a href="{{ route('home') }}" class="text-indigo-600 hover:text-indigo-500">Continue Shopping</a>
        </div>
    </div>

    <!-- Cart Content -->
    <div class="w-full h-full flex flex-col bg-white">
        <div class="flex-1 overflow-y-auto px-4 sm:px-6 py-6">
            @if (!empty($cartItems))
                <ul class="divide-y divide-gray-200">
                    @foreach($cartItems as $item)
                    <li class="flex py-6">
                        <div class="w-24 h-24 overflow-hidden rounded-md border border-gray-200">
                            <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" class="w-full h-full object-cover">
                        </div>
                        <div class="ml-4 flex flex-1 flex-col">
                            <div class="flex justify-between text-base font-medium text-gray-900">
                                <h3>{{ $item['name'] }}</h3>
                                <p class="ml-4">${{ number_format($item['subtotal'], 2) }}</p>
                            </div>
                            <div class="flex justify-between items-end mt-auto">
                                <form action="{{ route('cart.update', $item['id']) }}" method="POST" class="flex items-center space-x-2">
                                    @csrf
                                    @method('PUT')

                                    <button type="submit" name="action" value="decrease" class="px-2 bg-gray-200 rounded">−</button>

                                    <input type="number" value="{{ $item['quantity'] }}" min="1" class="w-12 text-center border rounded" readonly>

                                    <button type="submit" name="action" value="increase" class="px-2 bg-gray-200 rounded">+</button>
                                </form>

                                <form action="{{ route('order.removeItem', $item['id']) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="font-medium text-indigo-600 hover:text-indigo-500">Remove</button>
                                </form>
                            </div>
                        </div>
                    </li>
                    @endforeach
                </ul>
            @else
                <p>Your cart is empty.</p>
            @endif
        </div>

        <!-- Cart Footer -->
        <div class="border-t border-gray-200 bg-gray-100 sm:px-6 py-6">
            @if (!empty($cartItems))
                <div class="flex justify-between text-base font-medium text-gray-900">
                    <p>Subtotal</p>
                    <p>${{ number_format($total, 2) }}</p>
                </div>
                <p class="mt-0.5 text-sm text-gray-500">Shipping and taxes calculated at checkout.</p>
                <div class="mt-6">
                    <a href="{{ route('order.checkout') }}" class="flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-6 py-3 text-base font-medium text-white shadow-xs hover:bg-indigo-700">Proceed to Checkout</a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
