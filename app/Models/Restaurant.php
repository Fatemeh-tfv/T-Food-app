<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    /** @use HasFactory<\Database\Factories\RestaurantFactory> */
    use HasFactory;

    protected $fillable=['user_id','name', 'address','phone','status','shipping_fee'];
    public function users()
    {
        return $this->belongsTo(User::class);
    }

    public function menuCategories()
    {
        return $this->hasMany(MenuCategory::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
