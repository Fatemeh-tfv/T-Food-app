<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Restaurant;
use Illuminate\Support\Facades\Auth;

class RestaurantDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $restaurant = $user->restaurant;

        if (!$restaurant) {
            abort(403, 'No restaurant profile associated with your account.');
        }
        
        $restaurantId = $restaurant->id;

        // All orders
        $orders = Order::where('restaurant_id', $restaurantId)->latest()->get();

        // $status = request('status');

        // $orders = Order::where('restaurant_id', $restaurantId)
        //     ->when($status, fn($query) => $query->where('status', $status))
        //     ->latest()
        //     ->get();

        $totalOrders = $orders->count();
        $completedOrders = $orders->where('status', 'delivered')->count();
        $totalRevenue = $orders->where('status', 'delivered')->sum(fn ($order) => $order->total_price + $order->shipping_fee - $order->discount);

        // Revenue chart data
        $revenue = Order::where('restaurant_id', $restaurantId)
            ->where('status', 'delivered')
            ->selectRaw('DATE(created_at) as date, SUM(total_price + shipping_fee - discount) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $foodItems = $restaurant->menuCategories()
        ->with('foodItems')
        ->get()
        ->pluck('foodItems')
        ->flatten();

        $categories= $restaurant->menuCategories()->get();

        $revenueLabels = $revenue->pluck('date');
        $revenueData = $revenue->pluck('total');

        $shippingFee = $restaurant->shipping_fee ?? 10;

        $recentOrders = $orders->take(10); // or whatever you prefer

        return view('restaurants.dashboard', compact(
            'totalOrders',
            'completedOrders',
            'totalRevenue',
            'revenueLabels',
            'revenueData',
            'shippingFee',
            'restaurant',
            'recentOrders',
            'foodItems',
            'categories',
        ));
    }

    public function editProfile()
    {
        $restaurant= Restaurant::where('user_id', auth()->id())->firstOrFail();
        return view('restaurants.profile', compact('restaurant'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name'=>'required|string|max:255',
            'address'=>'required|string|max:255',
            'phone'=>'required|string|max:255',
            'description'=>'nullable|string',
            'logo'=>'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $restaurant=Restaurant::where('user_id', auth()->id())->firstOrFail();

        $restaurant->name = $request->name;
        $restaurant->address = $request->address;
        $restaurant->phone = $request->phone;
        $restaurant->description = $request->description;

        if($request->hasFile('logo'))
        {
            $logoPath= $request->file('logo')->store('logos', 'public');
            $restaurant->logo= $logoPath;
        }

        $restaurant->save();

        return redirect()->route('restaurant.dashboard')->with('success', 'Profile updated successfully.');
    }

    public function updateOrderStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,delivered',
        ]);

        $order->status = $validated['status'];
        $order->save();

        return back()->with('success', 'Order status updated successfully.');
    }

    public function updateShippingFee(Request $request)
    {
        $request->validate([
            'shipping_fee' => 'required|numeric|min:0',
        ]);
    
        $restaurant = auth()->user()->restaurant;
        $restaurant->shipping_fee = $request->shipping_fee;
        $restaurant->save();
    
        return back()->with('success', 'Shipping fee updated.');
    }

}
