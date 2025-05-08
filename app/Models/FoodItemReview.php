<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoodItemReview extends Model
{
    /** @use HasFactory<\Database\Factories\FoodItemReviewFactory> */
    use HasFactory;

    protected $fillable = ['food_item_id', 'user_id', 'rating', 'review'];

    public function foodItem(){
        return $this->belongsTo(FoodItem::class, 'food_item_id');
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
