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
                        <button id="filter-btn" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                            Filter
                        </button>
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
                                        <td class="px-6 py-4">
                                            {{ $item->invoice_id }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $item->total_qty_item }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $item->total_bayar ? 'Rp ' . number_format($item->total_bayar, 0, ',', '.') : '-' }}
                                        </td>
                                        <td class="px-6 py-4">
                                            @if ($item->bukti_pembayaran)
                                                <a href="{{ route('admin.transaction.proof', $item->id) }}" target="_blank" class="text-blue-500 hover:underline">
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
                                            {{ $item->created_at->format('d M Y H:i') }}
                                        </td>
                                        <td>
                                            @if ($item->status_pembayaran == 'pending' || $item->status_pembayaran == 'waiting')
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
                                                    </div>
                                                @endif
                                            @endif
                                        </td>
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
    <!-- SheetJS for Excel Export -->
    <script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
    <!-- jsPDF for PDF Export -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.7.0/jspdf.plugin.autotable.min.js"></script>
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
        document.getElementById('filter-btn').addEventListener('click', filterTable);

        // Excel Export logic
        document.getElementById('export-excel').addEventListener('click', function() {
            const table = document.getElementById('transaction-table');
            // Clone table and remove hidden rows
            const clone = table.cloneNode(true);
            const rows = clone.querySelectorAll('tbody tr');
            rows.forEach(row => {
                if (row.style.display === 'none') row.remove();
                // Remove Actions column for export
                row.querySelectorAll('td:last-child, th:last-child').forEach(cell => cell.remove());
            });
            // Remove Actions header
            clone.querySelector('thead tr th:last-child').remove();
            // Export
            const wb = XLSX.utils.table_to_book(clone, {
                sheet: "Transactions"
            });
            XLSX.writeFile(wb, 'transactions.xlsx');
        });

        // PDF Export logic
        document.getElementById('export-pdf').addEventListener('click', function() {
            const table = document.getElementById('transaction-table');
            // Prepare headers
            const headers = [];
            table.querySelectorAll('thead tr th').forEach((th, idx) => {
                if (idx !== table.querySelectorAll('thead tr th').length - 1) { // skip Actions
                    headers.push(th.textContent.trim());
                }
            });
            // Prepare rows
            const data = [];
            table.querySelectorAll('tbody tr').forEach(row => {
                if (row.style.display !== 'none') {
                    const rowData = [];
                    row.querySelectorAll('td').forEach((td, idx) => {
                        if (idx !== row.querySelectorAll('td').length - 1) { // skip Actions
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
