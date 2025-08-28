<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    protected $fillable = [
        'id_kategori',
        'nama',
        'stok',
        'harga',
        'deskripsi',
        'foto_produk',
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'id_product');
    }

    public function sizes()
    {
        return $this->hasMany(ProdukSize::class, 'id_produk');
    }

    public function DetailOrder(){
        return $this->hasMany(DetailOrder::class, 'id_produk');
    }
}
