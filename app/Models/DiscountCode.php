<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DiscountCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'amount',
        'type',
        'max_uses',
        'uses',
        'expires_at',
    ];

    protected $dates = [
        'expires_at',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function isValid()
    {
        return (!$this->expires_at || $this->expires_at->isFuture()) &&
               (!$this->max_uses || $this->uses < $this->max_uses);
    }
}

