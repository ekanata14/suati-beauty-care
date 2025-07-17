@extends('layouts.admin')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <form class="p-6" method="POST" action="{{ route('admin.product.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" value="{{ $data->id }}">

                    <div class="mb-6">
                        <label for="id_kategori" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Category</label>
                        <select id="id_kategori" name="id_kategori"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            required>
                            <option value="" disabled>Select a category</option>
                            @forelse ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ old('id_kategori', $data->id_kategori) == $category->id ? 'selected' : '' }}>
                                    {{ $category->nama }}
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
                        <label for="nama" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Product Name</label>
                        <input type="text" id="nama" name="nama" value="{{ old('nama', $data->nama) }}"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="Product Name" required />
                        @error('nama')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="harga" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Price</label>
                        <input type="number" id="harga" name="harga" value="{{ old('harga', $data->harga) }}"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="Price" required />
                        @error('harga')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="deskripsi" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Description</label>
                        <textarea id="deskripsi" name="deskripsi"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="Product Description" required>{{ old('deskripsi', $data->deskripsi) }}</textarea>
                        @error('deskripsi')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="foto_produk" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Product Image</label>
                        @if ($data->foto_produk)
                            <div class="mb-4">
                                <img src="{{ asset('storage/' . $data->foto_produk) }}" alt="Product Image"
                                    class="w-32 h-32 object-cover">
                            </div>
                        @endif
                        <input type="file" id="foto_produk" name="foto_produk"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            accept="image/*" />
                        @error('foto_produk')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Product Sizes</label>
                        <div id="sizes-container">
                            @php
                                $sizes = old('sizes', $data->sizes ?? []);
                            @endphp
                            @if($sizes)
                                @foreach($sizes as $i => $size)
                                    <div class="flex mb-2 items-center size-row">
                                        <input type="text" name="sizes[{{ $i }}][size]" value="{{ $size['size'] ?? '' }}" placeholder="Size" class="mr-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg p-2.5" required>
                                        @error("sizes.$i.size")
                                            <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                        @enderror
                                        <input type="number" name="sizes[{{ $i }}][stock]" value="{{ $size['stock'] ?? '' }}" placeholder="Stock" class="mr-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg p-2.5" required min="0">
                                        @error("sizes.$i.stock")
                                            <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                        @enderror
                                        <button type="button" class="remove-size-row text-red-500">Remove</button>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <button type="button" id="add-size-row" class="mt-2 btn-green">Add Size</button>
                        @error('sizes')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                        @if($errors->has('sizes.*.size') || $errors->has('sizes.*.stock'))
                            <p class="mt-2 text-sm text-red-600 dark:text-red-500">Please check size and stock fields.</p>
                        @endif
                    </div>

                    <div class="w-full flex justify-end">
                        <button type="submit" class="btn-primary">Update</button>
                    </div>
                </form>

                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        let sizesContainer = document.getElementById('sizes-container');
                        let addSizeBtn = document.getElementById('add-size-row');

                        function getSizeRowsCount() {
                            return sizesContainer.querySelectorAll('.size-row').length;
                        }

                        addSizeBtn.addEventListener('click', function () {
                            let index = getSizeRowsCount();
                            let div = document.createElement('div');
                            div.className = 'flex mb-2 items-center size-row';
                            div.innerHTML = `
                                <input type="text" name="sizes[${index}][size]" placeholder="Size" class="mr-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg p-2.5" required>
                                <input type="number" name="sizes[${index}][stock]" placeholder="Stock" class="mr-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg p-2.5" required min="0">
                                <button type="button" class="remove-size-row text-red-500">Remove</button>
                            `;
                            sizesContainer.appendChild(div);
                        });

                        sizesContainer.addEventListener('click', function (e) {
                            if (e.target.classList.contains('remove-size-row')) {
                                e.target.parentElement.remove();
                                // Re-index the name attributes
                                Array.from(sizesContainer.querySelectorAll('.size-row')).forEach(function(row, i){
                                    row.querySelector('input[type="text"]').setAttribute('name', `sizes[${i}][size]`);
                                    row.querySelector('input[type="number"]').setAttribute('name', `sizes[${i}][stock]`);
                                });
                            }
                        });
                    });
                </script>
            </div>
        </div>
    </div>
@endsection

