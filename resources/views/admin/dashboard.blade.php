@extends('template.layouts.app')

@section('title', 'Admin Dashboard')

@section('content')

@extends('template.layouts.session')

<div class="max-w-6xl mx-auto p-6">

    <h1 class="text-2xl font-bold mb-6">System Admin Dashboard</h1>

    {{-- Stats Overview --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-white p-4 shadow rounded">
            <p class="text-gray-600">Total Restaurants</p>
            <h3 class="text-2xl font-bold">{{ $totalRestaurants }}</h3>
        </div>
        <div class="bg-white p-4 shadow rounded">
            <p class="text-gray-600">Total Orders</p>
            <h3 class="text-2xl font-bold">{{ $totalOrders }}</h3>
        </div>
        <div class="bg-white p-4 shadow rounded">
            <p class="text-gray-600">Completed Orders</p>
            <h3 class="text-2xl font-bold">{{ $completedOrders }}</h3>
        </div>
        <div class="bg-white p-4 shadow rounded">
            <p class="text-gray-600">Average Order Value</p>
            <h3 class="text-2xl font-bold">${{ $averageOrderValue }}</h3>
        </div>
    </div>

    {{-- Add Restaurant User --}}
    <div class="bg-white p-6 shadow rounded mb-8">
        <h2 class="text-xl font-semibold mb-4">Add New Restaurant User</h2>

        <form action="{{ route('admin.restaurants.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label for="name" class="block font-semibold">Restaurant's Admin Name</label>
                <input type="text" name="name" class="w-full border rounded px-3 py-2" required>
            </div>

            <div>
                <label for="email" class="block font-semibold">Email</label>
                <input type="email" name="email" class="w-full border rounded px-3 py-2" required>
            </div>

            <div>
                <label for="password" class="block font-semibold">Password</label>
                <input type="password" name="password" class="w-full border rounded px-3 py-2" required>
            </div>

            <div>
                <label for="password_confirmation" class="block font-semibold">Confirm Password</label>
                <input type="password" name="password_confirmation" class="w-full border rounded px-3 py-2" required>
            </div>

            <div class="text-right">
                <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded hover:bg-indigo-700">
                    Add User
                </button>
            </div>
        </form>
    </div>

    {{-- Recent Restaurants --}}
    <div class="bg-white p-6 shadow rounded mb-6">
        <h2 class="text-lg font-semibold mb-4">Recent Restaurants</h2>
        <ul>
            @foreach ($recentRestaurants as $restaurant)
                <li class="py-2 border-b">{{ $restaurant->name }} - {{ $restaurant->created_at->format('M d, Y') }}</li>
            @endforeach
        </ul>
    </div>

    {{-- Recent Orders --}}
    <div class="bg-white p-6 shadow rounded">
        <h2 class="text-lg font-semibold mb-4">Recent Orders</h2>
        <ul>
            @foreach ($recentOrders as $order)
                <li class="py-2 border-b">
                    Order #{{ $order->id }} - ${{ $order->total_price }} - {{ $order->created_at->format('M d, Y') }}
                </li>
            @endforeach
        </ul>
    </div>

</div>
@endsection
