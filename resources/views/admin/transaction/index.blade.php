@extends('layouts.admin')

@section('content')
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <!-- Filter Section -->
                    <div class="mb-4 flex flex-wrap gap-4 items-center">
                        <div>
                            <label for="filter-date-from" class="block text-sm font-medium mb-1">From Date</label>
                            <input type="date" id="filter-date-from" class="border rounded px-2 py-1" />
                        </div>
                        <div>
                            <label for="filter-date-to" class="block text-sm font-medium mb-1">To Date</label>
                            <input type="date" id="filter-date-to" class="border rounded px-2 py-1" />
                        </div>
                        <button id="clear-filter-btn" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                            Clear Filter
                        </button>
                        <script>
                            document.getElementById('clear-filter-btn').addEventListener('click', function() {
                                document.getElementById('filter-date-from').value = '';
                                document.getElementById('filter-date-to').value = '';
                                filterTable();
                            });
                        </script>
                        <button id="export-excel" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                            Export to Excel
                        </button>
                        <button id="export-pdf" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                            Export to PDF
                        </button>
                    </div>
                    <div class="relative overflow-x-auto">
                        <table id="transaction-table"
                            class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">No</th>
                                    <th scope="col" class="px-6 py-3">Invoice ID</th>
                                    <th scope="col" class="px-6 py-3">Total Quantity</th>
                                    <th scope="col" class="px-6 py-3">Total Payment</th>
                                    <th scope="col" class="px-6 py-3">Payment Proof</th>
                                    <th scope="col" class="px-6 py-3">Payment Status</th>
                                    <th scope="col" class="px-6 py-3">Address</th>
                                    <th scope="col" class="px-6 py-3">Created At</th>
                                    <th scope="col" class="px-6 py-3">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="transaction-table-body">
                                @forelse ($datas as $item)
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200"
                                        data-date="{{ $item->created_at->format('Y-m-d') }}">
                                        <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                            {{ $loop->iteration }}
                                        </td>
                                        <td class="px-6 py-4"> {{ $item->invoice_id }} </td>
                                        <td class="px-6 py-4">
                                            {{ $item->total_qty_item }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $item->total_bayar ? 'Rp ' . number_format($item->total_bayar, 0, ',', '.') : '-' }}
                                        </td>
                                        <td class="px-6 py-4">
                                            @if ($item->bukti_pembayaran)
                                                <a href="{{ route('admin.transaction.proof', $item->id) }}" target="_blank"
                                                    class="text-blue-500 hover:underline">
                                                    View Proof
                                                </a>
                                            @else
                                                <span class="text-gray-400">No Proof</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-2">
                                            @php
                                                $status = strtolower($item->status_pembayaran);
                                                $badgeClasses = [
                                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                                    'waiting' => 'bg-blue-100 text-blue-800',
                                                    'paid' => 'bg-green-100 text-green-800',
                                                    'denied' => 'bg-red-100 text-red-800',
                                                ];
                                                $class = $badgeClasses[$status] ?? 'bg-gray-100 text-gray-800';
                                            @endphp
                                            <span class="px-2 py-1 rounded text-xs font-semibold {{ $class }}">
                                                {{ ucfirst($item->status_pembayaran) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-2">
                                            {{ $item->alamat }}
                                        </td>
                                        <td class="px-4 py-2">
                                            {{ $item->created_at->format('d M Y H:i') }}
                                        </td>
                                        <td>
                                            @if ($item->status_pembayaran == 'waiting')
                                                <div class="flex space-x-2">
                                                    <form action="{{ route('admin.transaction.update.status') }}"
                                                        method="POST" class="confirm-form">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="id" value="{{ $item->id }}">
                                                        <input type="hidden" name="status_pembayaran" value="denied">
                                                        <button type="submit"
                                                            class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600 confirm-button"
                                                            data-message="Are you sure you want to deny this payment?">
                                                            Denied
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('admin.transaction.update.status') }}"
                                                        method="POST" class="confirm-form">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="id" value="{{ $item->id }}">
                                                        <input type="hidden" name="status_pembayaran" value="paid">
                                                        <button type="submit"
                                                            class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 confirm-button"
                                                            data-message="Are you sure you want to confirm this payment?">
                                                            Confirm
                                                        </button>
                                                    </form>
                                                    <a href="{{ route('admin.transaction.detail', $item->id) }}"
                                                        class="btn-primary">Detail</a>
                                                </div>
                                                <script>
                                                    document.querySelectorAll('.confirm-button').forEach(button => {
                                                        button.addEventListener('click', function(e) {
                                                            e.preventDefault();
                                                            const form = this.closest('.confirm-form');
                                                            const message = this.getAttribute('data-message');
                                                            Swal.fire({
                                                                title: 'Confirmation',
                                                                text: message,
                                                                icon: 'warning',
                                                                showCancelButton: true,
                                                                confirmButtonColor: '#3085d6',
                                                                cancelButtonColor: '#d33',
                                                                confirmButtonText: 'Yes',
                                                                cancelButtonText: 'No'
                                                            }).then((result) => {
                                                                if (result.isConfirmed) {
                                                                    form.submit();
                                                                }
                                                            });
                                                        });
                                                    });
                                                </script>
                                            @elseif ($item->status_pembayaran == 'pending')
                                                <a href="{{ route('admin.transaction.detail', $item->id) }}"
                                                    class="btn-primary">Detail</a>
                                            @else
                                                @if ($item->status_pembayaran == 'denied')
                                                    <div class="px-4 py-2 bg-red-500 text-white rounded w-fit">
                                                        Payment Denied
                                                    </div>
                                                @elseif($item->status_pembayaran == 'paid')
                                                    <div class="flex gap-2">
                                                        <div
                                                            class="px-4 py-2 bg-green-500 text-white rounded w-fit flex items-center">
                                                            Payment Confirmed
                                                        </div>
                                                        <a href="{{ route('admin.transaction.detail', $item->id) }}"
                                                            class="btn-primary">Detail</a>
                                                        @if ($item->pengiriman)
                                                            @if ($item->pengiriman->status === 'pending')
                                                                <button type="button" onclick="openShippingModal(this)"
                                                                    data-id="{{ $item->id }}"
                                                                    data-invoice="{{ $item->invoice_id ?? '-' }}"
                                                                    data-user="{{ $item->order->user->name ?? 'Guest' }}"
                                                                    data-address="{{ $item->pengiriman->alamat_tujuan ?? '' }}"
                                                                    data-ongkir="{{ $item->pengiriman->biaya_ongkir ?? 0 }}"
                                                                    data-kurir="{{ $item->pengiriman->kurir ?? '' }}"
                                                                    data-layanan="{{ $item->pengiriman->layanan_kurir ?? '' }}"
                                                                    data-catatan="{{ $item->pengiriman->catatan ?? '' }}"
                                                                    class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600 text-xs">
                                                                    + Pengiriman
                                                                </button>
                                                            @endif
                                                        @endif
                                                    </div>
                                                @endif
                                            @endif
                                        </td>
                                        {{ $item->pengiriman ? '' : '' }}
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                            No data available.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="shippingModal" tabindex="-1" aria-hidden="true"
        class="hidden fixed inset-0 z-50 flex items-center justify-center w-full h-full bg-gray-900 bg-opacity-50">

        <div class="relative p-4 w-full max-w-2xl h-auto">
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">

                {{-- Header --}}
                <div class="flex justify-between items-start p-4 rounded-t border-b dark:border-gray-600">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                        Update Pengiriman: <span id="modal_invoice_title"></span>
                    </h3>
                    <button type="button" onclick="closeShippingModal()"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>

                {{-- Form dengan Multipart untuk File Upload --}}
                <form action="{{ route('admin.pengiriman.store') }}" method="POST" enctype="multipart/form-data"
                    class="p-6 space-y-6">
                    @csrf
                    <input type="hidden" name="id_transaksi" id="modal_id_transaksi">

                    {{-- Bagian Informasi (Read Only) --}}
                    <div
                        class="bg-blue-50 dark:bg-gray-600 p-4 rounded-lg mb-4 border border-blue-100 dark:border-gray-500">
                        <h4 class="text-sm font-bold text-blue-800 dark:text-blue-100 mb-3 uppercase tracking-wide">
                            Informasi Pengiriman</h4>
                        <div class="grid grid-cols-6 gap-4">
                            {{-- Nama Penerima (Info) --}}
                            <div class="col-span-6 sm:col-span-3">
                                <span
                                    class="block text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Penerima</span>
                                <div id="text_user_name" class="text-sm font-semibold text-gray-900 dark:text-white mt-1">
                                </div>
                            </div>

                            {{-- Biaya Ongkir (Info) --}}
                            <div class="col-span-6 sm:col-span-3">
                                <span class="block text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Biaya
                                    Ongkir</span>
                                <div id="text_ongkir" class="text-sm font-semibold text-gray-900 dark:text-white mt-1">
                                </div>
                            </div>

                            {{-- Alamat Tujuan (Info) --}}
                            <div class="col-span-6">
                                <span class="block text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Alamat
                                    Tujuan</span>
                                <div id="text_alamat"
                                    class="text-sm text-gray-800 dark:text-gray-200 mt-1 bg-white dark:bg-gray-700 p-2 rounded border border-gray-200 dark:border-gray-600">
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="border-gray-200 dark:border-gray-600">

                    {{-- Bagian Input (Editable) --}}
                    <div class="grid grid-cols-6 gap-6">
                        {{-- Kurir --}}
                        <div class="col-span-6 sm:col-span-3">
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kurir <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="kurir" id="modal_kurir" required
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                placeholder="Contoh: JNE / J&T">
                        </div>

                        {{-- Layanan --}}
                        <div class="col-span-6 sm:col-span-3">
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Layanan
                                (Opsional)</label>
                            <input type="text" name="layanan_kurir" id="modal_layanan"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                placeholder="Contoh: REG / YES">
                        </div>

                        {{-- Bukti Pembayaran / Resi Foto --}}
                        <div class="col-span-6">
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nomor Resi</label>
                            <input type="text" name="no_resi" id="modal_no_resi"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                placeholder="Nomor Resi Pengiriman">
                        </div>

                        {{-- Bukti Pembayaran / Resi Foto --}}
                        <div class="col-span-6">
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Bukti Pembayaran /
                                Foto Resi</label>
                            <input type="file" name="foto_bukti" accept="image/*"
                                class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Format: JPG, PNG, JPEG (Max 2MB).</p>
                        </div>

                        {{-- Catatan --}}
                        <div class="col-span-6">
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Catatan
                                (Opsional)</label>
                            <textarea name="catatan" id="modal_catatan" rows="2"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                placeholder="Tambahkan catatan pengiriman..."></textarea>
                        </div>
                    </div>

                    <div class="flex items-center p-6 space-x-2 rounded-b border-t border-gray-200 dark:border-gray-600">
                        <button type="submit"
                            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Simpan
                            Data</button>
                        <button type="button" onclick="closeShippingModal()"
                            class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- SheetJS for Excel Export -->
    <script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
    <!-- jsPDF for PDF Export -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.7.0/jspdf.plugin.autotable.min.js"></script>
    <script>
        function openShippingModal(button) {
            // --- 1. AMBIL DATA DARI TOMBOL ---
            const id = button.getAttribute('data-id');
            const invoice = button.getAttribute('data-invoice');
            const user = button.getAttribute('data-user');
            const address = button.getAttribute('data-address');
            const ongkir = button.getAttribute('data-ongkir');
            const kurir = button.getAttribute('data-kurir');
            const layanan = button.getAttribute('data-layanan');
            const catatan = button.getAttribute('data-catatan');

            // --- 2. SET DATA KE INPUT HIDDEN & JUDUL ---
            // Pastikan input hidden ini ada di HTML: <input type="hidden" id="modal_id_transaksi">
            const inputId = document.getElementById('modal_id_transaksi');
            if (inputId) inputId.value = id;

            const titleInvoice = document.getElementById('modal_invoice_title');
            if (titleInvoice) titleInvoice.innerText = invoice;

            // --- 3. SET DATA KE INFORMASI TEXT (READ ONLY) ---
            // Perhatikan ID-nya diawali dengan 'text_', bukan 'modal_'
            // Dan kita menggunakan .innerText karena ini DIV/SPAN, bukan INPUT

            const textUser = document.getElementById('text_user_name');
            if (textUser) textUser.innerText = user;

            const textAlamat = document.getElementById('text_alamat');
            if (textAlamat) textAlamat.innerText = address;

            const textOngkir = document.getElementById('text_ongkir');
            if (textOngkir) {
                let ongkirVal = parseFloat(ongkir);
                textOngkir.innerText = isNaN(ongkirVal) ? 'Rp 0' : 'Rp ' + ongkirVal.toLocaleString('id-ID');
            }

            // --- 4. SET DATA KE INPUT FORM (EDITABLE) ---
            // Ini masih menggunakan .value karena elemennya adalah INPUT/TEXTAREA

            const inputKurir = document.getElementById('modal_kurir');
            if (inputKurir) inputKurir.value = kurir;

            const inputLayanan = document.getElementById('modal_layanan');
            if (inputLayanan) inputLayanan.value = layanan;

            const inputCatatan = document.getElementById('modal_catatan');
            if (inputCatatan) inputCatatan.value = catatan;

            // Reset Input File
            const inputFile = document.querySelector('input[name="foto_bukti"]');
            if (inputFile) inputFile.value = '';

            // --- 5. TAMPILKAN MODAL ---
            const modal = document.getElementById('shippingModal');
            if (modal) modal.classList.remove('hidden');
        }

        function closeShippingModal() {
            const modal = document.getElementById('shippingModal');
            if (modal) modal.classList.add('hidden');
        }

        window.onclick = function(event) {
            const modal = document.getElementById('shippingModal');
            if (event.target == modal) {
                closeShippingModal();
            }
        }
    </script>
    <script>
        // Filtering logic with date range
        function filterTable() {
            const from = document.getElementById('filter-date-from').value;
            const to = document.getElementById('filter-date-to').value;
            const rows = document.querySelectorAll('#transaction-table-body tr');
            let visibleCount = 0;
            rows.forEach((row, idx) => {
                const rowDateStr = row.getAttribute('data-date');
                let show = true;
                if (rowDateStr) {
                    const rowDate = new Date(rowDateStr);
                    if (from) {
                        const fromDate = new Date(from);
                        if (rowDate < fromDate) show = false;
                    }
                    if (to) {
                        const toDate = new Date(to);
                        if (rowDate > toDate) show = false;
                    }
                }
                row.style.display = show ? '' : 'none';
                if (show) {
                    // update No column
                    row.querySelector('td').textContent = ++visibleCount;
                }
            });
        }
        document.getElementById('filter-date-from').addEventListener('change', filterTable);
        document.getElementById('filter-date-to').addEventListener('change', filterTable);

        // Excel Export logic
        document.getElementById('export-excel').addEventListener('click', function() {
            const table = document.getElementById('transaction-table');
            // Prepare headers
            const headers = [];
            table.querySelectorAll('thead tr th').forEach((th, idx, arr) => {
                if (idx !== arr.length - 1) { // skip Actions
                    headers.push(th.textContent.trim());
                }
            });
            // Prepare rows
            const data = [];
            table.querySelectorAll('tbody tr').forEach(row => {
                if (row.style.display !== 'none') {
                    const rowData = [];
                    row.querySelectorAll('td').forEach((td, idx, arr) => {
                        if (idx !== arr.length - 1) { // skip Actions
                            rowData.push(td.textContent.trim());
                        }
                    });
                    data.push(rowData);
                }
            });
            // Create worksheet and workbook
            const ws = XLSX.utils.aoa_to_sheet([headers, ...data]);
            const wb = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(wb, ws, "Transactions");
            XLSX.writeFile(wb, "transactions.xlsx");
        });

        // PDF Export logic
        document.getElementById('export-pdf').addEventListener('click', function() {
            const table = document.getElementById('transaction-table');
            // Prepare headers
            const headers = [];
            table.querySelectorAll('thead tr th').forEach((th, idx, arr) => {
                if (idx !== arr.length - 1) { // skip Actions
                    headers.push(th.textContent.trim());
                }
            });
            // Prepare rows
            const data = [];
            table.querySelectorAll('tbody tr').forEach(row => {
                if (row.style.display !== 'none') {
                    const rowData = [];
                    row.querySelectorAll('td').forEach((td, idx, arr) => {
                        if (idx !== arr.length - 1) { // skip Actions
                            rowData.push(td.textContent.trim());
                        }
                    });
                    data.push(rowData);
                }
            });
            // Generate PDF
            const {
                jsPDF
            } = window.jspdf;
            const doc = new jsPDF();
            doc.text("Transaction List", 14, 14);
            doc.autoTable({
                head: [headers],
                body: data,
                startY: 20,
                styles: {
                    fontSize: 8
                },
                headStyles: {
                    fillColor: [41, 128, 185]
                }
            });
            doc.save('transactions.pdf');
        });
    </script>
@endsection
