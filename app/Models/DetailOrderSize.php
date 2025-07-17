<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailOrderSize extends Model
{
    protected $fillable = [
        'id_detail_order',
        'size',
        'qty',
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk', 'id');
    }
}
