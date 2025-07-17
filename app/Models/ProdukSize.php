<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProdukSize extends Model
{
    protected $fillable = [
        'id_produk',
        'size',
        'stock',
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk');
    }
}
