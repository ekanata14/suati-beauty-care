<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailOrder extends Model
{
    protected $fillable = [
        'id_produk',
        'id_order',
        'qty',
        'harga',
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk', 'id');
    }

    public function sizes()
    {
        return $this->hasMany(DetailOrderSize::class, 'id_detail_order', 'id');
    }
}
