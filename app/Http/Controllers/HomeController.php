<?php

namespace App\Http\Controllers;
use App\Models\Restaurant;
use GuzzleHttp\Psr7\Request;

class HomeController extends Controller
{
    public function index()
        {
            $restaurants = Restaurant::with('menuCategories.foodItems.review')->get( );

            $restaurantsWithRatings = $restaurants->map(function ($restaurant) {
                // Flatten all foodItems from menuCategories
                $foodItems = $restaurant->menuCategories->flatMap(function ($category) {
                    return $category->foodItems;
                });
            
                // Now get all ratings
                $ratings = $foodItems->flatMap(function ($item) {
                    return optional($item->review)->pluck('rating') ?? collect();
                });
            
                $averageRating = $ratings->count() > 0 ? round($ratings->avg(), 1) : 0;
            
                $restaurant->average_rating = $averageRating;
            
                return $restaurant;
            });            
            

            return view('template.home', ['restaurants' => $restaurantsWithRatings]);
        }
}