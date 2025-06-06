<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuCategory extends Model
{
    /** @use HasFactory<\Database\Factories\MenuCategoryFactory> */
    use HasFactory;

    protected $guarded=[];
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function foodItems()
    {
        return $this->hasMany(FoodItem::class);
    }
}
