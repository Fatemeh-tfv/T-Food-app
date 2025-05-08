@extends('template.layouts.app')

@section('title', 'Restaurant Dashboard')

@section('content')

@extends('template.layouts.session')

<div class="max-w-7xl mx-auto py-10 px-6">
    <h1 class="text-2xl font-bold mb-6">Welcome, {{ auth()->user()->name }}</h1>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-lg shadow text-center">
            <p class="text-sm text-gray-500">Total Orders</p>
            <p class="text-2xl font-bold">{{ $totalOrders }}</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow text-center">
            <p class="text-sm text-gray-500">Completed Orders</p>
            <p class="text-2xl font-bold">{{ $completedOrders }}</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow text-center">
            <p class="text-sm text-gray-500">Total Revenue</p>
            <p class="text-2xl font-bold">${{ number_format($totalRevenue, 2) }}</p>
        </div>
    </div>

    <!-- Chart Section -->
    <div class="bg-white p-6 rounded-lg shadow mb-8">
        <h2 class="text-lg font-semibold mb-4">Revenue Overview</h2>
        <canvas id="revenueChart" height="100"></canvas>
    </div>

    <!-- Shipping Fee Update -->
    <div class="bg-white p-6 rounded-lg shadow mb-8">
        <h2 class="text-lg font-semibold mb-4">Manage Shipping Fee</h2>
        <form action="{{ route('restaurant.shipping.update') }}" method="POST">
            @csrf
            <div class="flex items-center space-x-4">
                <input type="number" step="0.01" name="shipping_fee" value="{{ $restaurant->shipping_fee ?? 10.00 }}" class="border p-2 rounded w-32" required>
                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">Update</button>
            </div>
        </form>
    </div>

    <!-- Recent Orders Filter + Table -->
    <div class="bg-white p-6 rounded-lg shadow">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold">Recent Orders</h2>
            <form method="GET" action="{{ route('restaurant.dashboard') }}" class="flex items-center gap-2">
                <label class="text-sm">Filter by status:</label>
                <select name="status" onchange="this.form.submit()" class="border p-2 rounded">
                    <option value="">All</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                </select>
            </form>
        </div>

        <table class="w-full text-left">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-2">Order ID</th>
                    <th class="p-2">Customer</th>
                    <th class="p-2">Total</th>
                    <th class="p-2">Status</th>
                    <th class="p-2">Date</th>
                    <th class="p-2">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentOrders as $order)
                <tr class="border-t">
                    <td class="p-2">#{{ $order->id }}</td>
                    <td class="p-2">{{ $order->user->name }}</td>
                    <td class="p-2">${{ number_format($order->total_price, 2) }}</td>
                    <td class="p-2">
                        <form method="POST" action="{{ route('restaurant.orders.updateStatus', $order->id) }}">
                            @csrf
                            @method('PATCH')
                            <select name="status" onchange="this.form.submit()" class="border rounded p-1 text-sm">
                                <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="confirmed" {{ $order->status === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                            </select>
                        </form>
                    </td>
                    <td class="p-2">{{ $order->created_at->format('M d, Y') }}</td>
                    <td class="p-2">
                        <a href="#" class="text-indigo-600 hover:underline">View</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Manage Food Items Section -->
    <div class="bg-white p-6 rounded-lg shadow mt-10">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold">Your Menu</h2>
            <a href="{{ route('foods.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                + Add Food Item
            </a>
        </div>

        <table class="w-full text-left">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-2">Name</th>
                    <th class="p-2">Price</th>
                    <th class="p-2">Category</th>
                    <th class="p-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($foodItems as $item)
                <tr class="border-t">
                    <td class="p-2">{{ $item->name }}</td>
                    <td class="p-2">${{ number_format($item->price, 2) }}</td>
                    <td class="p-2">{{ $item->category->name }}</td>
                    <td class="p-2 space-x-2">
                        <a href="javascript:void(0)"
                            onclick="openEditModal('{{ $item->id }}', '{{ $item->name }}', '{{ $item->price }}', '{{ $item->description }}', '{{ $item->menu_category_id }}')"
                            class="text-blue-600 hover:underline">Edit</a>

                        <form action="{{ route('foods.destroy', $item->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if (session('toast_success'))
    <div 
        id="toast" 
        class="fixed bottom-5 right-5 bg-green-600 text-white px-4 py-3 rounded shadow-lg z-50 transition-opacity duration-300"
    >
        {!! session('toast_success') !!}
    </div>

    <script>
        setTimeout(() => {
            const toast = document.getElementById('toast');
            if (toast) {
                toast.style.opacity = '0';
                setTimeout(() => toast.remove(), 500);
            }
        }, 5000); // toast disappears after 5 seconds
    </script>
    @endif

</div>

<!-- Edit Modal -->
<div id="editModal" class="fixed inset-0 z-1050 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div id="editModalContent" class="bg-white rounded-lg p-6 max-w-lg w-full max-h-[90vh] overflow-y-auto relative">
        <button onclick="closeModal()" class="absolute top-2 right-2 text-gray-500 hover:text-black">&times;</button>
        <h3 class="text-lg font-semibold mb-4">Edit Food Item</h3>

        <form id="editForm" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <input type="hidden" name="id" id="foodId">
            <!-- <input type="hidden" name="categoryId" id="menu_category_id"> -->

            <div class="mb-4">
                <label class="block font-semibold">Category</label>
                <select name="menu_category_id" id="menu_category_id" class="w-full border rounded px-3 py-2" required>
                    <option value="" disabled selected>Select a category</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" id="category_{{ $category->id }}">
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="mb-4">
                <label class="block text-sm">Name</label>
                <input type="text" name="name" id="foodName" class="w-full border rounded p-2" required>
            </div>

            <div class="mb-4">
                <label class="block text-sm">Price</label>
                <input type="number" step="0.01" name="price" id="foodPrice" class="w-full border rounded p-2" required>
            </div>

            <div class="mb-4">
                <label class="block text-sm">Description</label>
                <textarea name="description" id="foodDescription" class="w-full border rounded p-2"></textarea>
            </div>

            <div class="mb-4">
                <label class="block text-sm">Image</label>
                <input type="file" name="image" id="foodImage" class="w-full border rounded p-2">
            </div>

            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">Update</button>
        </form>
    </div>
</div>

<script>
const updateFoodUrl = "{{ route('foods.update', ['id' => 'FOOD_ID']) }}"; 

function openEditModal(id, name, price, description, categoryId) {
    document.getElementById('editModal').classList.remove('hidden');
    document.getElementById('editModal').classList.add('flex');

    document.getElementById('foodId').value = id;
    document.getElementById('foodName').value = name;
    document.getElementById('foodPrice').value = price;
    document.getElementById('foodDescription').value = description;
    // document.getElementById('menu_category_id').value = categoryId;

    document.querySelector('select[name="menu_category_id"]').value = categoryId;


    // Update the form action dynamically
    const form = document.getElementById('editForm');
    form.action = updateFoodUrl.replace('FOOD_ID', id);
}

function closeModal() {
    document.getElementById('editModal').classList.add('hidden');
}

window.addEventListener('click', function(event) {
    if (event.target === document.getElementById('editModal')) {
        closeModal();
    }
});

</script>
<script>
    const ctx = document.getElementById('revenueChart').getContext('2d');
    const revenueChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($revenueLabels) !!},
            datasets: [{
                label: 'Revenue ($)',
                data: {!! json_encode($revenueData) !!},
                borderColor: 'rgba(99, 102, 241, 1)',
                backgroundColor: 'rgba(99, 102, 241, 0.2)',
                tension: 0.3,
                fill: true,
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>

@endsection
