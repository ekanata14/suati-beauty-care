@extends('layouts.admin')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <form class="p-6" method="POST" action="{{ route('admin.product.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-6">
                        <label for="id_kategori"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Category</label>
                        <select id="id_kategori" name="id_kategori"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            required>
                            <option value="" disabled selected>Select a category</option>
                            @forelse ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ old('id_kategori') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @empty
                                <option value="" disabled>No categories available</option>
                            @endforelse
                        </select>
                        @error('id_kategori')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-6">
                        <label for="nama" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Product
                            Name</label>
                        <input type="text" id="nama" name="nama" value="{{ old('nama') }}"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="Product Name" required />
                        @error('nama')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-6">
                        <label for="stok"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Stock</label>
                        <input type="number" id="stok" name="stok" value="{{ old('stok') }}"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="Stock" required />
                        @error('stok')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-6">
                        <label for="harga"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Price</label>
                        <input type="number" id="harga" name="harga" value="{{ old('harga') }}"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="Price" required />
                        @error('harga')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-6">
                        <label for="deskripsi"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Description</label>
                        <textarea id="deskripsi" name="deskripsi"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="Product Description" required>{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-6">
                        <label for="foto_produk"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Product Image</label>
                        <input type="file" id="foto_produk" name="foto_produk"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            accept="image/*" required />
                        @error('foto_produk')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="w-full flex justify-end">
                        <button type="submit" class="btn-primary">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
