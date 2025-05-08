<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Restaurant;

class RestaurantController extends Controller
{
    public function show($id)
    {
        $restaurant = Restaurant::with('menuCategories.foodItems')->findOrFail($id);
        return view('template.menu', compact('restaurant'));
    }
}
