<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pengiriman', function (Blueprint $table) {
            $table->id();

            // Relasi Transaksi
            $table->unsignedBigInteger('id_transaksi');
            $table->foreign('id_transaksi')->references('id')->on('transaksis')->onDelete('cascade');

            // Relasi User (Penerima)
            $table->unsignedBigInteger('id_user');
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');

            // Data Kurir & Resi
            $table->string('kurir'); // Contoh: JNE, J&T, Internal Courier
            $table->string('layanan_kurir')->nullable(); // Contoh: REG, YES, OKE (Opsional tapi berguna)
            $table->string('no_resi')->nullable(); // Nullable, karena saat status 'pending' resi belum ada

            // Ongkir (Opsional, jika nominal ongkir real berbeda dengan estimasi saat checkout)
            $table->decimal('biaya_ongkir', 12, 2)->default(0);

            // Alamat Snapshot (PENTING: Simpan string lengkap alamat di sini atau di tabel transaksi)
            $table->text('alamat_tujuan');

            // Status & Catatan
            // Enum lebih aman daripada string biasa: 'pending', 'shipped', 'delivered', 'returned'
            $table->enum('status', ['pending', 'diproses', 'dikirim', 'diterima', 'dikembalikan'])->default('pending');
            $table->text('catatan')->nullable(); // Untuk admin mencatat: "Barang titip di satpam"

            // Bukti Foto (Opsional: path file foto resi)
            $table->string('foto_bukti')->nullable();

            // Tracking Waktu Spesifik
            $table->timestamp('tgl_dikirim')->nullable(); // Diisi saat admin input resi
            $table->timestamp('tgl_diterima')->nullable(); // Diisi saat status berubah jadi diterima

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengiriman');
    }
};
