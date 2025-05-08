<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class FoodItem extends Model
{
    use SoftDeletes;
    /** @use HasFactory<\Database\Factories\FoodItemFactory> */
    use HasFactory;

    protected $fillable = [
        'menu_category_id',
        'name',
        'description',
        'price',
        'image',
        'restaurant_id',
    ];
    public function category()
    {
        return $this->belongsTo( MenuCategory::class,'menu_category_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function review(){
        return $this->hasMany(FoodItemReview::class);
    }

    public function averageRating()
    {
        return $this->review()->avg('rating');
    }

}
