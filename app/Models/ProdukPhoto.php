<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProdukPhoto extends Model
{
    protected $fillable = [
        'id_produk',
        'url'
    ];
}
