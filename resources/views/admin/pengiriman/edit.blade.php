@extends('layouts.admin')

@section('content')
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Pengiriman: {{ $item->transaksi->invoice_id ?? '-' }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">

                <form action="{{ route('admin.pengiriman.update', $item->id) }}" method="POST">
                    @csrf
                    @method('PUT') <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium mb-1">Kurir</label>
                            <input type="text" name="kurir" value="{{ old('kurir', $item->kurir) }}" class="w-full border-gray-300 rounded-md">
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">No. Resi</label>
                            <input type="text" name="no_resi" value="{{ old('no_resi', $item->no_resi) }}" class="w-full border-gray-300 rounded-md" placeholder="Input saat barang dikirim">
                            @error('no_resi') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Status</label>
                            <select name="status" class="w-full border-gray-300 rounded-md">
                                @foreach(['pending', 'diproses', 'dikirim', 'diterima', 'dikembalikan'] as $st)
                                    <option value="{{ $st }}" {{ $item->status == $st ? 'selected' : '' }}>
                                        {{ ucfirst($st) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Biaya Ongkir</label>
                            <input type="number" name="biaya_ongkir" value="{{ old('biaya_ongkir', $item->biaya_ongkir) }}" class="w-full border-gray-300 rounded-md">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium mb-1">Catatan</label>
                            <textarea name="catatan" rows="3" class="w-full border-gray-300 rounded-md">{{ old('catatan', $item->catatan) }}</textarea>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end gap-2">
                        <a href="{{ route('admin.pengiriman.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded">Batal</a>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Simpan Perubahan</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection
