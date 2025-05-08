<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FoodItem;
use App\Models\MenuCategory;

class FoodItemController extends Controller
{
    public function show($id){
        $food=FoodItem::with('category.restaurant', 'review')->findOrFail($id);
        return view('template.item',compact('food'));
    }

    public function create()
    {
        $restaurant= auth()->user()->restaurant;
        $categories= MenuCategory::where('restaurant_id', $restaurant->id)->get();

        return view('restaurants.foods.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_name' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|max:2048',
        ]);
    
        $restaurantId = auth()->user()->restaurant->id;
    
        // Create or find category
        $category = MenuCategory::firstOrCreate([
            'restaurant_id' => $restaurantId,
            'name' => $validated['category_name'],
        ]);
    
        // Prepare food item data
        $foodData = [
            'menu_category_id' => $category->id,
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'],
        ];
    
        if ($request->hasFile('image')) {
            $foodData['image'] = $request->file('image')->store('food_image', 'public');
        }
    
        FoodItem::create($foodData);
    
        return redirect()->route('restaurant.dashboard')->with('success', 'Food item added successfully.');
    }
    
    public function destroy(FoodItem $foodItem)
    {
        $foodItem->delete();

        $undoUrl=route('foods.undo',$foodItem->id);
        $message = "Food item deleted. <a href='$undoUrl' class='underline text-blue-200'>Undo</a>";

        return redirect()->back()->with('toast_success', $message);
    }

    public function undoDelete($id)
    {
        $foodItem= FoodItem::withTrashed()->findOrFail($id);

        if($foodItem->trashed())
        {
            $foodItem->restore();
        return redirect()->back()->with('toast_success', 'Undo successful. Food item restored.');
        }

        return redirect()->back()->with('toast_success', 'Item was not deleted or already restored.');
    }
    public function edit(FoodItem $foodItem)
    {
        $categories = MenuCategory::where('restaurant_id', auth()->user()->restaurant->id)->get();

        return view('restaurant.foods.edit', compact('foodItem', 'categories'));
    }

    public function update(Request $request, $id)
    {

        $foodItem= FoodItem::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'menu_category_id' => 'required|exists:menu_categories,id',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);

        $foodItem->update([
            'name' => $request->name,
            'price' => $request->price,
            'menu_category_id' => $request->menu_category_id,
            'description' => $request->description,
            'image' => $request->hasFile('image') 
                ? $request->file('image')->store('food_images', 'public')
                : $foodItem->image,
        ]);

        return redirect()->route('restaurant.dashboard')->with('success', 'Food item updated successfully.');
    }

}
