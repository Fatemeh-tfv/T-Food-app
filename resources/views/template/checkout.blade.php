@extends('template.layouts.app')

@section('title', 'Checkout - Yummy')

@section('content')
@extends('template.layouts.session')
<div class="max-w-6xl mx-auto py-10 px-6 grid grid-cols-1 md:grid-cols-3 gap-8">
    <!-- Left Side: Payment Form -->
    <div class="md:col-span-2 bg-white p-6 rounded-lg shadow-sm">
        <form action="{{ route('order.finalize') }}" method="POST">
            @csrf
            <x-forms.input label="Email (Optional)" name="email" type="email" placeholder="Enter email" value="{{ $order->user->email ?? ''}}"/>

            <x-forms.input label="Recipient's name" name="recipient_name" type="text" class="w-full border p-3 rounded-lg mb-4" placeholder="Enter Recipient's Name" value="{{ $order->recipient_name ?? $order->user->name ?? '' }}" required/>

            <x-forms.input label="Address" type="text" name="address" placeholder="Street Address" required/>

            <div class="grid grid-cols-3 gap-4 mt-4 mb-4">
                <input type="text" name="city" class="border p-3 rounded-lg" placeholder="City" required>
                <input type="text" name="state" class="border p-3 rounded-lg" placeholder="State" required>
                <input type="text" name="postal_code" class="border p-3 rounded-lg" placeholder="Postal Code" required>
            </div>

            <x-forms.input label="Phone Number" type="text" name="phone" placeholder="Enter Phone Number" required/>

            <div class="mt-4">
                <label for="notes" class="block mb-2 font-medium text-gray-700">Additional Notes</label>
                <textarea name="notes" id="notes" rows="4" class="w-full border p-3 rounded-lg" placeholder="Write any notes for the restaurant...">{{ old('notes', $order->notes ?? '') }}</textarea>
            </div>

            <label class="block mb-2 font-medium text-gray-700">Payment Method</label>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @php
                    $methods = ['cash' => 'Cash', 'card' => 'Card', 'paypal' => 'PayPal', 'other' => 'Other'];
                @endphp

                @foreach($methods as $value => $label)
                    <label class="relative cursor-pointer">
                        <input type="radio" name="payment_method" value="{{ $value }}" class="sr-only peer payment-option" required>
                        <div class="w-full p-4 border rounded-xl text-center peer-checked:border-indigo-600 peer-checked:bg-indigo-50 transition-all">
                            <div class="text-lg font-semibold capitalize flex justify-center items-center gap-2">
                                @if($value === 'cash')
                                    <i class="fas fa-money-bill-wave text-green-600"></i>
                                @elseif($value === 'card')
                                    <i class="fas fa-credit-card text-blue-600"></i>
                                @elseif($value === 'paypal')
                                    <i class="fab fa-paypal text-indigo-600"></i>
                                @else
                                    <i class="fas fa-wallet text-gray-600"></i>
                                @endif
                                {{ $label }}
                            </div>
                        </div>
                    </label>
                @endforeach
            </div>

            <div class="mt-4 flex items-center">
                <input type="checkbox" id="billing" class="mr-2">
                <label for="billing" class="text-sm text-gray-600">Billing address is the same as shipping address</label>
            </div>

            <button type="submit" class="w-full bg-indigo-600 text-white py-3 rounded-lg font-semibold mt-6 hover:bg-indigo-700">
                Pay <span id="final-total">${{ number_format(collect($cart)->sum('subtotal') + 10 - ($discount ?? 0), 2) }}</span>
            </button>
        </form>
    </div>

    <!-- Right Side: Order Summary -->
    <div class="bg-white p-6 rounded-lg shadow-sm">
        <h2 class="text-lg font-semibold mb-4">Order Summary</h2>
        <div class="divide-y">
            <div class="flex justify-between py-2">
                <p>Subtotal</p>
                <p>${{ number_format(collect($cart)->sum('subtotal'), 2) }}</p>
            </div>
            @if(isset($discount) && $discount > 0)
            <div class="flex justify-between py-2">
                <p>Discount <span class="bg-gray-200 text-xs px-2 py-1 rounded-md">CHEAPSKATE</span></p>
                <p class="text-red-500">- ${{ number_format($discount, 2) }}</p>
            </div>
            @endif
            <div class="flex justify-between py-2">
                <p>Shipping</p>
                <p id="shipping-fee">$10.00</p>
            </div>
            <div class="flex justify-between font-semibold text-lg py-2">
                <p>Total</p>
                <p id="summary-total">${{ number_format(collect($cart)->sum('subtotal') + 10 - ($discount ?? 0), 2) }}</p>
            </div>
        </div>

        <form method="POST" action="{{ route('order.applyDiscount') }}" class="mt-4">
            @csrf
            <label class="block mb-2">Discount Code</label>
            <div class="flex">
                <input type="text" name="discount_code" class="w-full border p-3 rounded-l-lg" placeholder="Enter code">
                <button class="bg-gray-300 px-4 py-3 rounded-r-lg" type="submit">Apply</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const shippingFee = 10;
        const subtotal = {{ collect($cart)->sum('subtotal') }};
        const discount = {{ $discount ?? 0 }};
        const paymentOptions = document.querySelectorAll('.payment-option');
        const finalTotalEl = document.getElementById('final-total');
        const summaryTotalEl = document.getElementById('summary-total');

        function updateTotal() {
            const total = (subtotal + shippingFee) - discount;
            const formatted = `$${total.toFixed(2)}`;
            finalTotalEl.textContent = formatted;
            summaryTotalEl.textContent = formatted;
        }

        paymentOptions.forEach(option => {
            option.addEventListener('change', updateTotal);
        });
    });
</script>
@endsection
