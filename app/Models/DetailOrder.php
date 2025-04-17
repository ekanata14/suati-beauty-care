<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailOrder extends Model
{
    protected $fillable = [
        'id_Produk',
        'id_Order',
        'qty',
        'harga',
    ];
}
