@extends('template.layouts.app')

@section('title', 'Order Status')

@section('content')
@extends('template.layouts.session')
    <div class="max-w-7xl mx-auto py-10 px-6">
        <h1 class="text-2xl font-bold mb-6">Order Summary</h1>

        <div class="bg-white p-6 rounded-lg shadow">
            <h2 class="text-lg font-semibold mb-4">Order #{{ $order->id }}</h2>
            <p><strong>Customer:</strong> {{ $order->user->name }}</p>
            <p><strong>Status:</strong> {{ ucfirst($order->status) }}</p>
            <p><strong>Total Price:</strong> ${{ number_format($order->total_price, 2) }}</p>
            <p><strong>Shipping Fee:</strong> ${{ number_format($order->shipping_fee, 2) }}</p>
            <p><strong>Discount:</strong> ${{ number_format($order->discount, 2) }}</p>
            <p><strong>Address:</strong> {{ $order->address }}, {{ $order->city }}, {{ $order->state }} - {{ $order->postal_code }}</p>
            <p><strong>Phone:</strong> {{ $order->phone }}</p>
            <p><strong>Notes:</strong> {{ $order->notes ?? 'No additional notes' }}</p>
        </div>

        <h3 class="text-lg font-semibold mt-8 mb-4">Order Items</h3>
        <div class="bg-white p-6 rounded-lg shadow mb-8">
            <table class="w-full text-left">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-2">Item Name</th>
                        <th class="p-2">Quantity</th>
                        <th class="p-2">Price</th>
                        <th class="p-2">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->orderItems as $item)
                        <tr class="border-t">
                            <td class="p-2">{{ $item->foodItem->name }}</td>
                            <td class="p-2">{{ $item->quantity }}</td>
                            <td class="p-2">${{ number_format($item->price, 2) }}</td>
                            <td class="p-2">${{ number_format($item->price * $item->quantity, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
