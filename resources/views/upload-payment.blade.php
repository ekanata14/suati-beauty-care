@extends('layouts.client')

@section('content')
    <section class="py-8 antialiased dark:bg-gray-900 md:py-16 h-screen">
        <form method="POST" action="{{ route('upload.payment.store') }}" class="mx-auto px-4 2xl:px-0 flex gap-4 justify-center" enctype="multipart/form-data">
            @csrf
            <div class="space-y-6 bg-white p-6 w-full md:w-3/4">
                <h4 class="text-xl font-semibold text-gray-900 dark:text-white">Upload Payment</h4>
                <div class="space-y-4">
                    <div class="space-y-2">
                        <dl class="flex items-center justify-between gap-4">
                            <dt class="text-gray-500 dark:text-gray-400">Total Price</dt>
                            <dd class="text-base font-medium text-gray-900 dark:text-white">{{ $transaction->total_bayar }}
                            </dd>
                        </dl>
                    </div>
                    <div class="space-y-2">
                        <dl class="flex items-center justify-between gap-4">
                            <dt class="text-gray-500 dark:text-gray-400">Total Item</dt>
                            <dd class="text-base font-medium text-gray-900 dark:text-white">
                                {{ $transaction->total_qty_item }}</dd>
                        </dl>
                    </div>

                    <dl class="flex items-center justify-between gap-4 border-t border-gray-200 pt-2 dark:border-gray-700">
                        <dt class="text-lg font-bold text-gray-900 dark:text-white">Total</dt>
                        <dd class="text-lg font-bold text-gray-900 dark:text-white">{{ $transaction->total_bayar }}</dd>
                    </dl>

                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="file_input">Upload
                        file</label>
                    <input
                        class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                        id="file_input" type="file" name="bukti_pembayaran" accept="image/*" required />
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-300" id="file_input_help">PNG, JPG, JPEG (MAX. 2MB)</p>

                </div>

                <div class="flex flex-col gap-2">
                    <input type="hidden" name="id" value="{{ $transaction->id }}">
                    <input type="hidden" name="total_qty_item" value="{{ $transaction->total_qty_item }}">
                    <input type="hidden" name="total_bayar" value="{{ $transaction->total_bayar }}">
                    <button type="submit" class="mt-4 btn-primary w-full">Checkout</button>
                    <a href="{{ route('products') }}"
                        class="w-full text-center rounded-lg  border border-gray-200 bg-white px-5  py-2.5 text-sm font-medium text-gray-900 hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:outline-none focus:ring-4 focus:ring-gray-100 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white dark:focus:ring-gray-700">Return
                        to Shopping</a>
                </div>
            </div>
        </form>
    </section>
@endsection
