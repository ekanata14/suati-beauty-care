@extends('layouts.client')

@section('content')
    {{-- PAYMENT UPLOAD SECTION --}}
    <section
        class="py-16 bg-gradient-to-br from-white via-blue-100 to-blue-200 dark:from-gray-900 dark:via-gray-800 dark:to-gray-700 min-h-screen">
        <form method="POST" action="{{ route('upload.payment.store') }}" enctype="multipart/form-data"
            class="mx-auto px-4 2xl:px-0 flex gap-4 justify-center" data-aos="fade-up" data-aos-duration="500"
            data-aos-delay="300">
            @csrf
            <div class="space-y-6 bg-white dark:bg-gray-800 p-8 w-full md:w-3/4 rounded-2xl shadow-xl">
                <h4 class="text-2xl font-bold text-gray-900 dark:text-white">Upload Payment</h4>

                <div class="space-y-4">
                    <div class="flex justify-between text-sm text-gray-600 dark:text-gray-400">
                        <span>Total Price</span>
                        <span class="font-medium text-gray-900 dark:text-white">IDR
                            {{ number_format($transaction->total_bayar, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-sm text-gray-600 dark:text-gray-400">
                        <span>Total Item</span>
                        <span class="font-medium text-gray-900 dark:text-white">{{ $transaction->total_qty_item }}</span>
                    </div>
                    <div
                        class="flex justify-between text-lg font-bold text-gray-900 dark:text-white border-t pt-4 dark:border-gray-700">
                        <span>Total</span>
                        <span>IDR {{ number_format($transaction->total_bayar, 0, ',', '.') }}</span>
                    </div>
                    <div class="mt-4">
                        <span>
                            Nomor Rekening:
                            <span id="account-number" class="font-mono select-all">1234567890</span>
                            <button type="button" onclick="copyAccountNumber()"
                                class="ml-2 px-2 py-1 text-xs bg-blue-500 text-white rounded hover:bg-blue-600 focus:outline-none">
                                Copy
                            </button>
                        </span>
                        <script>
                            function copyAccountNumber() {
                                const accNum = document.getElementById('account-number').textContent;
                                navigator.clipboard.writeText(accNum).then(function() {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Disalin!',
                                        text: 'Nomor rekening berhasil disalin.',
                                        timer: 1200,
                                        showConfirmButton: false
                                    });
                                });
                            }
                        </script>
                    </div>
                    <div>
                        <label for="file_input" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">Upload
                            File</label>
                        <input id="file_input" name="bukti_pembayaran" type="file" accept="image/*" required
                            class="block w-full text-sm text-gray-900 dark:text-gray-400 border border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer bg-gray-50 dark:bg-gray-700 focus:outline-none">
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-300">PNG, JPG, JPEG (MAX. 2MB)</p>
                    </div>
                </div>

                <input type="hidden" name="id" value="{{ $transaction->id }}">
                <input type="hidden" name="total_qty_item" value="{{ $transaction->total_qty_item }}">
                <input type="hidden" name="total_bayar" value="{{ $transaction->total_bayar }}">

                <div class="flex flex-col gap-3">
                    <button type="button" onclick="confirmPayment(event)"
                        class="btn-primary w-full px-5 py-2.5 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-500 dark:hover:bg-blue-600 dark:focus:ring-blue-800">
                        Checkout
                    </button>
                    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                    <script>
                        function confirmPayment(e) {
                            Swal.fire({
                                title: 'Are you sure?',
                                text: "Do you want to upload this payment proof?",
                                icon: 'question',
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Yes, upload it!'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    e.target.closest('form').submit();
                                }
                            });
                        }
                    </script>
                    <a href="{{ route('products') }}"
                        class="text-center rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-blue-700 dark:hover:text-white px-5 py-2.5 text-sm font-medium focus:ring-4 focus:outline-none focus:ring-gray-100 dark:focus:ring-gray-700">
                        Return to Shopping
                    </a>
                </div>
            </div>
        </form>
    </section>
@endsection
