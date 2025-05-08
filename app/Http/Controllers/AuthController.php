<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\LoginController;

class AuthController extends Controller
{
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8|confirmed',
        // 'role' => 'required|in:customer,restaurant,admin',
    ]);

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role' => 'customer',
    ]);

    // Auto-create a restaurant profile if user is a restaurant owner
    // if ($user->isRestaurantOwner()) {
    //     Restaurant::create([
    //         'user_id' => $user->id,
    //         'name' => $user->name . "'s Restaurant",
    //         'address' => 'To be updated',
    //         'phone' => '000-000-0000',
    //     ]);
    // }

    Auth::login($user);

    return $user->isRestaurantOwner()
        ? redirect()->route('restaurant.dashboard')
        : redirect()->route('home');
}
}
