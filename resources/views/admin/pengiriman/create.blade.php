@extends('layouts.admin')

@section('content')
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Buat Pengiriman Manual</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">

                <form action="{{ route('admin.pengiriman.store') }}" method="POST">
                    @csrf <div class="mb-4">
                        <label class="block text-sm font-medium mb-1">Pilih Transaksi (Paid & Belum Dikirim)</label>
                        <select name="id_transaksi" class="w-full border-gray-300 rounded-md" required>
                            <option value="">-- Pilih Invoice --</option>
                            @foreach($transaksis as $trx)
                                <option value="{{ $trx->id }}">
                                    {{ $trx->invoice_id }} - {{ $trx->user->name ?? 'Guest' }} (Rp {{ number_format($trx->total_bayar) }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-1">Kurir</label>
                            <input type="text" name="kurir" class="w-full border-gray-300 rounded-md" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Layanan</label>
                            <input type="text" name="layanan_kurir" class="w-full border-gray-300 rounded-md" placeholder="Contoh: REG/YES">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Biaya Ongkir</label>
                            <input type="number" name="biaya_ongkir" class="w-full border-gray-300 rounded-md" value="0" required>
                        </div>
                    </div>

                    <div class="mt-4">
                        <label class="block text-sm font-medium mb-1">Alamat Tujuan Lengkap</label>
                        <textarea name="alamat_tujuan" rows="3" class="w-full border-gray-300 rounded-md" required></textarea>
                    </div>

                    <div class="mt-6 flex justify-end gap-2">
                        <a href="{{ route('admin.pengiriman.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded">Batal</a>
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded">Simpan Data</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection
