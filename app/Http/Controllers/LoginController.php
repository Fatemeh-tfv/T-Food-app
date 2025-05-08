<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Restaurant;
use App\Models\User;
class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            // 'role' => 'required|in:customer,restaurant,admin'
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();

            // Redirect users based on their role
            if ($user->role=='admin') {
                return redirect()->route('admin.dashboard');
            } elseif ($user->role=='restaurant') {
                return redirect()->route('restaurant.dashboard');
            } else {
                return redirect()->route('home');
            }
        }

        return back()->withErrors(['email' => 'Invalid credentials.']);
    }


    public function logout()
    {
        Auth::logout();
        return redirect()->route('login.form');
    }
}

