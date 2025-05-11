<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'id_product',
        'id_user',
        'rating',
        'review',
    ];

    public function product()
    {
        return $this->belongsTo(Produk::class, 'id_product');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
