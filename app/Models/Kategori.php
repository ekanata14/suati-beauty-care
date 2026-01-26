<?php

namespace App\Models;

use App\Traits\RecordActivity;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use RecordActivity;
    protected $fillable = [
        'nama',
        'created_by',
        'updated_by',
    ];

    public function produk()
    {
        return $this->hasMany(Produk::class, 'id_kategori');
    }
}
