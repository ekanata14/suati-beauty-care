<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'id_user',
        'total',
        'status',
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk');
    }

    public function konsumen()
    {
        return $this->belongsTo(Konsumen::class, 'id_konsumen');
    }
}
