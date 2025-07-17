<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartDetailSize extends Model
{
    protected $fillable = [
        'id_cart',
        'size',
        'qty',
    ];

    public function cart()
    {
        return $this->belongsTo(Cart::class, 'id_cart');
    }
}
