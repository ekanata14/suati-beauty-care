<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = [
        'id_user',
        'id_produk',
        'qty',
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
