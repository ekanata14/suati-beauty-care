@extends('layouts.admin')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <form class="p-6" method="POST" action="{{ route('admin.category.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-6">
                        <label for="nama" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Category Name</label>
                        <input type="text" id="nama" name="nama" value="{{ old('nama') }}"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="Category Name" required />
                        @error('nama')
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
