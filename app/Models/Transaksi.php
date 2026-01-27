<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $fillable = [
        'id_order',
        'invoice_id',
        'total_qty_item',
        'total_bayar',
        'bukti_pembayaran',
        'status_pembayaran',
        'alamat'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'id_order');
    }

    public function pengiriman(){
        return $this->hasOne(Pengiriman::class, 'id_transaksi', 'id');
    }
}
