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
}
