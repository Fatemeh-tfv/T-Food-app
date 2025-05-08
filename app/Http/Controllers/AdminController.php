<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Restaurant;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Count all restaurants
        $totalRestaurants = Restaurant::count();

        // Count all orders
        $totalOrders = Order::count();

        // Count completed orders (assuming 'completed' is a status)
        $completedOrders = Order::where('status', 'delivered')->count();

        // Calculate average order value
        $averageOrderValue = Order::avg('total_price') ?? 0;

        // Get recent restaurants (latest 5)
        $recentRestaurants = Restaurant::latest()->take(5)->get();

        // Get recent orders (latest 5)
        $recentOrders = Order::latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalRestaurants',
            'totalOrders',
            'completedOrders',
            'averageOrderValue',
            'recentRestaurants',
            'recentOrders'
        ));
    }

    public function storeRestaurant(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:6',
        ]);

        $user=User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'restaurant',
        ]);


        Restaurant::create([
            'user_id' => $user->id,
            'name'=>'To be updated',
            'address'=>'To be updated',
            'phone'=>'To be updated',
            'status'=>'active',
            'shipping_fee' => 10.0,
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Restaurant user created successfully.');
    }
}