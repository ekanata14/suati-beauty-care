<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Transaksi;
use App\Models\Kategori;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $viewData = [
            'title' => 'Transaction Management',
            'datas' => Transaksi::latest()->get(),
        ];

        return view('admin.transaction.index', $viewData);
    }

    public function updateStatus(Request $request)
    {
        $validatedData = $request->validate([
            'id' => 'required',
            'status_pembayaran' => 'required|string',
        ]);

        try {
            DB::beginTransaction();

            $transaksi = Transaksi::findOrFail($validatedData['id']);
            $transaksi->status_pembayaran = $validatedData['status_pembayaran'];
            $transaksi->save();

            DB::commit();

            return redirect()->back()->with('success', 'Status updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to update status: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $viewData = [
            'title' => 'History Detail',
            'transaction' => Transaksi::with('order.detailOrder.produk')->where('id', $id)->first(),
            'categories' => Kategori::all(),
        ];

        return view('admin.transaction.detail', $viewData);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function getTransactionProof(string $id)
    {
        $transaction = Transaksi::findOrFail($id);
        $path = storage_path('app/private/' . $transaction->bukti_pembayaran);

        if (!file_exists($path)) {
            abort(404, 'File not found.');
        }

        return response()->file($path);
    }
}
