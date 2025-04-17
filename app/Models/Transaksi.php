<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $fillable = [
        'id_order',
        'total_qty_item',
        'total_bayar',
        'bukti_pembayaran',
        'status_pembayaran',
    ];
}
