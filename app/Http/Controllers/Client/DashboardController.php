<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

// Models
use App\Models\Produk;
use App\Models\Kategori;
use App\Models\Order;
use App\Models\DetailOrder;
use App\Models\Transaksi;

class DashboardController extends Controller
{
    public function index()
    {
        $viewData = [
            'title' => 'Home',
            'products' => Produk::all()
        ];

        return view('welcome', $viewData);
    }

    public function updateProfile(Request $request)
    {
        try {
            DB::beginTransaction();

            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'username' => 'required|string|max:255|unique:users,username,' . auth()->user()->id,
                'email' => 'required|email|max:255|unique:users,email,' . auth()->user()->id,
                'tanggal_lahir' => 'required|date',
                'jenis_kelamin' => 'required',
                'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'telepon' => 'required|string|max:15',
            ]);

            $user = auth()->user();
            $user->update([
                'name' => $validatedData['name'],
                'username' => $validatedData['username'],
                'email' => $validatedData['email'],
            ]);

            $konsumen = $user->konsumen;
            if ($request->hasFile('foto_profil')) {
                if ($konsumen->foto_profil) {
                    Storage::disk('public')->delete($konsumen->foto_profil);
                }
                $validatedData['foto_profil'] = $request->file('foto_profil')->store('foto_profil', 'public');
            }

            $konsumen->update([
                'tanggal_lahir' => $validatedData['tanggal_lahir'],
                'jenis_kelamin' => $validatedData['jenis_kelamin'],
                'foto_profil' => $validatedData['foto_profil'] ?? $konsumen->foto_profil,
                'telepon' => $validatedData['telepon'],
            ]);

            DB::commit();
            return back()->with('success', 'Profil berhasil diperbarui.');
        } catch (\Exception $e) {
            return $e->getMessage();
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat memperbarui profil.');
        }
    }

    public function about()
    {
        $viewData = [
            'title' => 'About',
        ];

        return view('about', $viewData);
    }

    public function products()
    {
        $viewData = [
            'title' => 'Products',
            'products' => Produk::orderBy('created_at', 'desc')->get(),
            'id' => 0,
            'categories' => Kategori::all(),
        ];

        return view('products', $viewData);
    }

    public function productsCategory($id)
    {
        $viewData = [
            'title' => 'Products',
            'products' => Produk::where('id_kategori', $id)->orderBy('created_at', 'desc')->get(),
            'id' => $id,
            'categories' => Kategori::all(),
        ];

        return view('products', $viewData);
    }


    public function searchProducts(Request $request)
    {
        if (!$request->nama) {
            return redirect()->route('products');
        } else {
            $products = Produk::where(function ($query) use ($request) {
                $query->where('nama', 'like', '%' . $request->nama . '%');
            })->orderBy('created_at', 'desc')->paginate(10);
        }
        $viewData = [
            'title' => 'Products',
            'products' => $products,
            'id' => 0,
            'categories' => Kategori::all(),
        ];

        return view("products", $viewData);
    }

    public function productDetail(string $id)
    {
        $viewData = [
            'title' => 'Products',
            'product' => Produk::find($id),
            'id' => 0,
            'categories' => Kategori::all(),
        ];

        return view('product-detail', $viewData);
    }

    public function addToOrder(Request $request)
    {
        try {
            DB::beginTransaction();

            $order = Order::where('id_user', auth()->user()->id)->where('status', 'pending')->latest()->first();

            if ($order == null) {
                $order = Order::create([
                    'id_user' => auth()->user()->id,
                    'total' => 0,
                    'status' => 'pending',
                ]);
            }

            $orderDetail = DetailOrder::where('id_order', $order->id)->where('id_produk', $request->id_produk)->first();
            if ($orderDetail == null) {
                DetailOrder::create([
                    'id_order' => $order->id,
                    'id_produk' => $request->id_produk,
                    'harga' => $request->harga,
                    'qty' => $request->qty,
                ]);
                $order->update([
                    'total' => $order->total + ($request->jumlah * $request->harga),
                ]);
            } else {
                $orderDetail->update([
                    'qty' => $orderDetail->qty + $request->qty,
                ]);
                $order->update([
                    'total' => $order->total + ($request->qty * $request->harga),
                ]);
            }

            DB::commit();
            return back()->with('success', 'Produk berhasil ditambahkan ke keranjang.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat menambahkan produk ke keranjang.');
        }
    }

    public function cart()
    {
        $viewData = [
            'title' => 'Keranjang',
            'order' => Order::with('detailOrder')->where('id_user', auth()->user()->id)->where('status', 'pending')->latest()->first(),
            'categories' => Kategori::all(),
        ];

        return view('cart', $viewData);
    }

    public function deleteFromCart(Request $request)
    {
        try {
            DB::beginTransaction();

            $orderDetail = DetailOrder::find($request->id);
            if ($orderDetail == null) {
                return back()->with('error', 'Produk tidak ditemukan.');
            }

            $order = Order::find($orderDetail->id_order);
            if ($order == null) {
                return back()->with('error', 'Keranjang tidak ditemukan.');
            }

            $order->update([
                'total' => $order->total - ($orderDetail->qty * $orderDetail->harga),
            ]);

            $orderDetail->delete();

            DB::commit();
            return back()->with('success', 'Produk berhasil dihapus dari keranjang.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat menghapus produk dari keranjang.');
        }
    }

    public function checkout(Request $request)
    {
        try {
            DB::beginTransaction();

            $validatedData = $request->validate([
                'id_order' => 'required',
                'total_qty_item' => 'required',
                'total_bayar' => 'required',
            ]);

            $order = Order::with('detailOrder')->where('id_user', auth()->user()->id)->where('status', 'pending')->latest()->first();

            if ($order == null) {
                return back()->with('error', 'Keranjang kosong.');
            }

            $invoiceId = 'INV-' . date('Ymd') . '-' . str_pad($order->id, 5, '0', STR_PAD_LEFT);

            $transaksi = Transaksi::create([
                'id_order' => $order->id,
                'invoice_id' => $invoiceId,
                'total_qty_item' => $validatedData['total_qty_item'],
                'total_bayar' => $validatedData['total_bayar'],
                'bukti_pembayaran' => 'pending',
                'status_pembayaran' => 'pending',
            ]);

            $order->update([
                'status' => 'checkout',
            ]);

            DB::commit();
            return redirect()->route('cart.upload.payment', $transaksi->id)->with('success', 'Checkout berhasil.');
        } catch (\Exception $e) {
            return $e->getMessage();
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat melakukan checkout.');
        }
    }

    public function uploadPayment(string $id)
    {
        $viewData = [
            'title' => 'Upload Bukti Pembayaran',
            'transaction' => Transaksi::with('order.detailOrder.produk')->where('id', $id)->first(),
            'categories' => Kategori::all(),
        ];

        return view('upload-payment', $viewData);
    }

    public function uploadPaymentStore(Request $request)
    {
        try {
            DB::beginTransaction();

            $validatedData = $request->validate([
                'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            ]);

            $transaksi = Transaksi::find($request->id);

            if ($transaksi == null) {
                return back()->with('error', 'Transaksi tidak ditemukan.');
            }

            if ($request->file('bukti_pembayaran')) {
                if ($transaksi->bukti_pembayaran != 'pending') {
                    Storage::delete($transaksi->bukti_pembayaran);
                }
                $validatedData['bukti_pembayaran'] = $request->file('bukti_pembayaran')->store('images/bukti-pembayaran');
            }

            $transaksi->update([
                'bukti_pembayaran' => $validatedData['bukti_pembayaran'],
                'status_pembayaran' => 'waiting',
            ]);

            DB::commit();
            return redirect()->route('history')->with('success', 'Bukti pembayaran berhasil diupload.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat mengupload bukti pembayaran.');
        }
    }

    public function history()
    {
        $viewData = [
            'title' => 'History',
            'transactions' => Transaksi::with('order.detailOrder.produk')->whereHas('order', function ($query) {
                $query->where('id_user', auth()->user()->id);
            })->latest()->paginate(10),
            'categories' => Kategori::all(),
        ];

        return view('history', $viewData);
    }

    public function historyDetail(string $id)
    {
        $viewData = [
            'title' => 'History',
            'transaction' => Transaksi::with('order.detailOrder.produk')->where('id', $id)->first(),
            'categories' => Kategori::all(),
        ];

        return view('history-detail', $viewData);
    }
}
