<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengiriman;
use App\Models\Transaksi; // Diperlukan untuk create manual
use Illuminate\Support\Facades\Storage; // Jangan lupa import ini
use Illuminate\Http\Request;


class PengirimanController extends Controller
{
    // --- 1. INDEX (Menampilkan Data) ---
    public function index()
    {
        $datas = Pengiriman::with(['transaksi', 'user'])
            ->latest()
            ->get();

        $viewData = [
            'title' => 'Pengiriman Management',
            'datas' => $datas,
        ];

        return view('admin.pengiriman.index', $viewData);
    }

    // --- 2. CREATE (Form Tambah Manual) ---
    public function create()
    {
        // Ambil transaksi yang BELUM punya pengiriman
        $transaksis = Transaksi::doesntHave('pengiriman')->where('status_pembayaran', 'paid')->get();

        $viewData = [
            'title' => 'Create Pengiriman Manual',
            'transaksis' => $transaksis,
        ];

        return view('admin.pengiriman.create', compact('transaksis'));
    }


    public function store(Request $request)
    {
        // 1. Validasi Input (Sesuaikan dengan field di Modal)
        // 'alamat_tujuan' dan 'biaya_ongkir' dihapus dari validasi karena tidak dikirim via form (disabled/text only)
        $validated = $request->validate([
            'id_transaksi' => 'required|exists:transaksis,id',
            'kurir' => 'required|string',
            'layanan_kurir' => 'nullable|string',
            'catatan' => 'nullable|string',
            // Validasi baru untuk upload foto
            'foto_bukti' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'no_resi' => 'required|string',
        ]);

        // 2. Ambil Data Transaksi
        $transaksi = Transaksi::with('order.user')->findOrFail($request->id_transaksi);

        // 3. Persiapkan Data Dasar
        $dataToSave = [
            'id_user' => $transaksi->order->user->id,
            'kurir' => $validated['kurir'],
            'layanan_kurir' => $validated['layanan_kurir'],
            'catatan' => $validated['catatan'],
            'tgl_dikirim' => now(), // Set tanggal kirim sekarang
            'no_resi' => $validated['no_resi'],
        ];

        // 4. Handle Logika Alamat & Ongkir
        // Cek apakah data pengiriman untuk transaksi ini sudah ada sebelumnya?
        $existingPengiriman = Pengiriman::where('id_transaksi', $request->id_transaksi)->first();

        if (!$existingPengiriman) {
            // JIKA DATA BARU (Create):
            // Kita wajib mengisi alamat & ongkir. Ambil dari data Transaksi/User.
            // Sesuaikan 'alamat_pengiriman' & 'total_ongkir' dengan nama kolom di tabel Transaksi Anda.
            $dataToSave['alamat_tujuan'] = $transaksi->alamat_pengiriman ?? $transaksi->user->alamat ?? '-';
            $dataToSave['biaya_ongkir'] = $transaksi->total_ongkir ?? 0;
            $dataToSave['status'] = 'diproses'; // Status awal default
        }
        // Jika update, kita biarkan alamat & ongkir apa adanya (tidak ditimpa),
        // kecuali Anda ingin meresetnya dari data transaksi setiap kali update.

        // 5. Handle Upload Foto Bukti
        if ($request->hasFile('foto_bukti')) {
            // Hapus file lama jika ada (agar server tidak penuh sampah file)
            if ($existingPengiriman && $existingPengiriman->foto_bukti) {
                Storage::disk('public')->delete($existingPengiriman->foto_bukti);
            }

            // Simpan file baru
            $path = $request->file('foto_bukti')->store('bukti_pengiriman', 'public');
            $dataToSave['foto_bukti'] = $path;

            // Opsional: Jika admin upload resi/bukti, otomatis ubah status jadi 'dikirim'
            $dataToSave['status'] = 'dikirim';
        }

        // 6. Simpan ke Database (Update or Create)
        // Mencari berdasarkan id_transaksi, lalu update/buat data sesuai array $dataToSave
        Pengiriman::updateOrCreate(
            ['id_transaksi' => $request->id_transaksi],
            $dataToSave
        );

        return redirect()->route('admin.pengiriman.index')->with('success', 'Data pengiriman berhasil diperbarui.');
    }

    public function confirmReceived($id)
    {
        $pengiriman = Pengiriman::findOrFail($id);

        // Update status menjadi 'diterima' dan set tanggal diterima
        $pengiriman->status = 'diterima';
        $pengiriman->tgl_diterima = now();
        $pengiriman->save();

        return back()->with('success', 'Pengiriman telah dikonfirmasi diterima.');

    }

    // --- 4. EDIT (Form Edit) ---
    public function edit($id)
    {
        $item = Pengiriman::with('transaksi')->findOrFail($id);
        return view('admin.pengiriman.edit', compact('item'));
    }

    // --- 5. UPDATE (Proses Update Data) ---
    public function update(Request $request, $id)
    {
        $pengiriman = Pengiriman::findOrFail($id);

        $validated = $request->validate([
            'kurir' => 'required|string',
            'no_resi' => 'nullable|string',
            'status' => 'required|in:pending,diproses,dikirim,diterima,dikembalikan',
            'catatan' => 'nullable|string',
            'biaya_ongkir' => 'numeric|min:0'
        ]);

        // Logika Auto Tanggal & Validasi Resi
        if ($validated['status'] == 'dikirim') {
            if (empty($validated['no_resi'])) {
                return back()->withErrors(['no_resi' => 'Nomor Resi WAJIB DIISI jika status diset ke DIKIRIM.']);
            }
            if ($pengiriman->status != 'dikirim') {
                $validated['tgl_dikirim'] = now();
            }
        }

        if ($validated['status'] == 'diterima' && $pengiriman->status != 'diterima') {
            $validated['tgl_diterima'] = now();
        }

        $pengiriman->update($validated);

        return redirect()->route('admin.pengiriman.index')->with('success', 'Status pengiriman diperbarui.');
    }

    // --- 6. DESTROY (Hapus Data) ---
    public function destroy($id)
    {
        $pengiriman = Pengiriman::findOrFail($id);
        $pengiriman->delete();

        return redirect()->back()->with('success', 'Data pengiriman dihapus.');
    }

    // --- 7. SHOW (Detail Data) ---
    public function show($id)
    {
        // Opsional jika butuh halaman detail khusus
        $item = Pengiriman::with(['transaksi.detail_transaksi', 'user'])->findOrFail($id);
        // return view('admin.pengiriman.show', compact('item'));
    }
}
