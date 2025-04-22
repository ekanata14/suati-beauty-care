@extends('layouts.client')

@section('content')
    <section class="bg-white py-8 antialiased dark:bg-gray-900 md:py-16 h-screen">
        <div class="mx-auto max-w-screen-xl px-4 2xl:px-0">
            <div class="mx-auto max-w-5xl">
                <div class="gap-4 lg:flex lg:items-center lg:justify-between">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white sm:text-2xl">History</h2>

                    {{-- <div class="mt-6 gap-4 space-y-4 sm:flex sm:items-center sm:space-y-0 lg:mt-0 lg:justify-end">
                        <div>
                            <label for="order-type"
                                class="sr-only mb-2 block text-sm font-medium text-gray-900 dark:text-white">Select order
                                type</label>
                            <select id="order-type"
                                class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder:text-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500 sm:w-[144px]">
                                <option selected>All orders</option>
                                <option value="ongoing">Ongoing</option>
                                <option value="completed">Completed</option>
                                <option value="denied">Denied</option>
                            </select>
                        </div>

                        <span class="inline-block text-gray-500 dark:text-gray-400"> from </span>

                        <div>
                            <label for="date"
                                class="sr-only mb-2 block text-sm font-medium text-gray-900 dark:text-white">Select
                                date</label>
                            <select id="date"
                                class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder:text-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500 sm:w-[144px]">
                                <option selected>this week</option>
                                <option value="this month">this month</option>
                                <option value="last 3 months">the last 3 months</option>
                                <option value="lats 6 months">the last 6 months</option>
                                <option value="this year">this year</option>
                            </select>
                        </div>

                        <button type="button"
                            class="w-full rounded-lg bg-primary-700 px-5 py-2.5 text-sm font-medium text-white hover:bg-primary-800 focus:outline-none focus:ring-4 focus:ring-primary-300   dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800 sm:w-auto">Add
                            return request</button>
                    </div> --}}
                </div>

                <div class="mt-6 flow-root sm:mt-8">
                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($transactions as $item)
                            <div class="relative grid grid-cols-2 gap-4 py-6 sm:grid-cols-4 lg:grid-cols-5">
                                <div class="col-span-2 content-center sm:col-span-4 lg:col-span-1">
                                    <a href="#"
                                        class="text-base font-semibold text-gray-900 hover:underline dark:text-white">{{ $item->invoice_id }}</a>
                                </div>

                                <div class="content-center">
                                    <div class="flex items-center gap-2">
                                        <svg class="h-4 w-4 text-gray-400" aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                            viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M4 10h16m-8-3V4M7 7V4m10 3V4M5 20h14a1 1 0 0 0 1-1V7a1 1 0 0 0-1-1H5a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1Zm3-7h.01v.01H8V13Zm4 0h.01v.01H12V13Zm4 0h.01v.01H16V13Zm-8 4h.01v.01H8V17Zm4 0h.01v.01H12V17Zm4 0h.01v.01H16V17Z" />
                                        </svg>
                                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                            {{ $item->created_at }}</p>
                                    </div>
                                </div>

                                <div class="content-center">
                                    <div class="flex items-start justify-end gap-2 sm:justify-start flex-col">
                                        <p class="text-sm text-gray-500 dark:text-gray-400"><span
                                                class="font-medium text-gray-900 dark:text-white">
                                                {{ $item->total_qty_item }}</span> items</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            <span class="font-medium text-gray-900 dark:text-white">
                                                {{ $item->total_bayar }}</span> IDR
                                        </p>
                                    </div>
                                </div>

                                <div class="absolute right-0 top-7 content-center sm:relative sm:right-auto sm:top-auto">
                                    @if ($item->status_pembayaran === 'pending')
                                        <span
                                            class="inline-flex items-center rounded bg-yellow-100 px-2.5 py-0.5 text-xs font-medium text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300">
                                            <svg class="me-1 h-3 w-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                                width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M12 8v4m0 4h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                            </svg>
                                            Pending
                                        </span>
                                    @elseif ($item->status_pembayaran === 'denied')
                                        <span
                                            class="inline-flex items-center rounded bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-800 dark:bg-red-900 dark:text-red-300">
                                            <svg class="me-1 h-3 w-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                                width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M12 8v4m0 4h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                            </svg>
                                            Denied
                                        </span>
                                    @elseif ($item->status_pembayaran === 'waiting')
                                        <span
                                            class="inline-flex items-center rounded bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                                            <svg class="me-1 h-3 w-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                                width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M12 8v4m0 4h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                            </svg>
                                            Waiting
                                        </span>
                                    @elseif ($item->status_pembayaran === 'paid')
                                        <span
                                            class="inline-flex items-center rounded bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800 dark:bg-green-900 dark:text-green-300">
                                            <svg class="me-1 h-3 w-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                                width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                            Paid
                                        </span>
                                    @endif
                                </div>

                                <div class="col-span-2 content-center sm:col-span-1 sm:justify-self-end">
                                    <a href="{{ route('history.detail', $item->id) }}"
                                        class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm font-medium text-gray-900 hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:outline-none focus:ring-4 focus:ring-gray-100 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white dark:focus:ring-gray-700 sm:w-auto">View
                                        details</a>
                                </div>
                            </div>

                        @empty
                            <div class="text-center">
                                <p class="text-gray-500">No transactions available</p>
                            </div>
                        @endforelse

                        <div class="mt-6">
                            {{ $transactions->links('pagination::tailwind') }}
                        </div>
                    </div>
                </div>
    </section>
@endsection
