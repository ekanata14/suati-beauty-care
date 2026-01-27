<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\RecordActivity;

class Pengiriman extends Model
{
    use HasFactory, RecordActivity;

    /**
     * Nama tabel di database.
     */
    protected $table = 'pengiriman';

    /**
     * Kolom yang boleh diisi secara massal (Mass Assignment).
     * Ini mencakup kolom dari schema rekomendasi sebelumnya.
     */
    protected $fillable = [
        'id_transaksi',
        'id_user',
        'kurir',
        'layanan_kurir', // Opsional (REG, YES, dll)
        'no_resi',
        'biaya_ongkir',
        'alamat_tujuan', // Snapshot alamat
        'status',        // pending, dikirim, diterima, dll
        'catatan',
        'foto_bukti',
        'tgl_dikirim',
        'tgl_diterima',
        'created_by',
        'updated_by'
    ];

    /**
     * Mengubah tipe data otomatis saat diakses.
     * Sangat berguna agar tgl_dikirim langsung jadi objek Carbon (bisa format tanggal).
     */
    protected $casts = [
        'tgl_dikirim' => 'datetime',
        'tgl_diterima' => 'datetime',
        'biaya_ongkir' => 'decimal:2',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relasi (Relationships)
    |--------------------------------------------------------------------------
    */

    /**
     * Relasi ke Transaksi (Many to One / One to One).
     * Pengiriman milik satu Transaksi.
     */
    public function transaksi()
    {
        // Parameter ke-2 'id_transaksi' wajib karena nama kolom FK Anda bukan default (transaksi_id)
        return $this->belongsTo(Transaksi::class, 'id_transaksi');
    }

    /**
     * Relasi ke User (Many to One).
     * Pengiriman milik satu User (Penerima).
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
