<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengiriman;
use App\Models\Transaksi; // Diperlukan untuk create manual
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

    // --- 3. STORE (Simpan Data Baru) ---
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_transaksi' => 'required|exists:transaksis,id',
            'kurir' => 'required|string',
            'layanan_kurir' => 'nullable|string',
            'biaya_ongkir' => 'required|numeric|min:0',
            'alamat_tujuan' => 'required|string',
            'catatan' => 'nullable|string',
        ]);

        // Ambil data user dari transaksi
        $transaksi = Transaksi::findOrFail($request->id_transaksi);

        Pengiriman::create([
            'id_transaksi' => $transaksi->id,
            'id_user' => $transaksi->id_user, // Asumsi ada kolom id_user di transaksi
            'kurir' => $validated['kurir'],
            'layanan_kurir' => $validated['layanan_kurir'],
            'biaya_ongkir' => $validated['biaya_ongkir'],
            'alamat_tujuan' => $validated['alamat_tujuan'],
            'catatan' => $validated['catatan'],
            'status' => 'pending', // Default awal
        ]);

        return redirect()->route('admin.pengiriman.index')->with('success', 'Data pengiriman berhasil dibuat manual.');
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
