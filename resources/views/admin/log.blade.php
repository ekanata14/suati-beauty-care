@extends('layouts.admin')

@section('content')

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-4">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">Riwayat Perubahan: {{ $item->name ?? $item->nama }}</h3>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="relative overflow-x-auto">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">
                                        Waktu
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        User (Pelaku)
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Aksi
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Perubahan Data
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($activities as $activity)
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
                                        {{-- Kolom Waktu --}}
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ $activity->created_at->format('d M Y H:i:s') }}
                                        </td>

                                        {{-- Kolom User --}}
                                        <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                            {{ $activity->causer->name ?? 'Sistem' }}
                                        </td>

                                        {{-- Kolom Aksi (Badge Conversion) --}}
                                        <td class="px-6 py-4">
                                            @if ($activity->description == 'created')
                                                <span
                                                    class="bg-green-100 text-green-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300">
                                                    Dibuat
                                                </span>
                                            @elseif($activity->description == 'updated')
                                                <span
                                                    class="bg-yellow-100 text-yellow-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-yellow-900 dark:text-yellow-300">
                                                    Diedit
                                                </span>
                                            @else
                                                <span
                                                    class="bg-blue-100 text-blue-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">
                                                    {{ $activity->description }}
                                                </span>
                                            @endif
                                        </td>

                                        {{-- Kolom Detail Perubahan --}}
                                        <td class="px-6 py-4">
                                            @if ($activity->event == 'updated')
                                                <ul class="list-disc list-inside space-y-1">
                                                    @foreach ($activity->properties['old'] as $key => $val)
                                                        @if ($val != $activity->properties['attributes'][$key])
                                                            <li>
                                                                <span
                                                                    class="font-semibold capitalize">{{ ucfirst($key) }}</span>:
                                                                <span
                                                                    class="text-red-600 dark:text-red-400 line-through mx-1">{{ $val }}</span>
                                                                &rarr;
                                                                <span
                                                                    class="text-green-600 dark:text-green-400 font-medium ml-1">{{ $activity->properties['attributes'][$key] }}</span>
                                                            </li>
                                                        @endif
                                                    @endforeach
                                                </ul>
                                            @elseif($activity->event == 'created')
                                                <span class="italic text-gray-500 dark:text-gray-400">Data baru
                                                    ditambahkan</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                            Tidak ada data riwayat perubahan.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        {{-- Jika activities menggunakan pagination, uncomment baris di bawah ini --}}
                        {{-- <div class="mt-4">
                    {{ $activities->links() }}
                </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
