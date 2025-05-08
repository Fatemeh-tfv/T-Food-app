<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    /** @use HasFactory<\Database\Factories\OrderFactory> */
    use HasFactory;

    protected $fillable = [
        'restaurant_id',
        'user_id',
        'payment_method',
        'payment_status',
        'status',
        'address',
        'phone',
        'notes',
        'city',
        'state',
        'postal_code',
        'recipient_name',
        'shipping_fee',
        'total_price',
    ];
    
    protected $attributes = [
        'shipping_fee' => 10.00, // Default value
    ];
    
    protected $guarded=[];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function discountCode() {
        return $this->belongsTo(DiscountCode::class);
    }
}
