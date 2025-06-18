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

    public function konsumen()
    {
        return $this->belongsTo(Konsumen::class, 'id_konsumen');
    }

    public function Transaksi()
    {
        return $this->hasOne(Transaksi::class, 'id_order');
    }

    public function detailOrder()
    {
        return $this->hasMany(DetailOrder::class, 'id_order', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
