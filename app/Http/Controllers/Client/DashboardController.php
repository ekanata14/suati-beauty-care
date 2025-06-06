<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

// Models
use App\Models\Produk;
use App\Models\Kategori;
use App\Models\Review;
use App\Models\Order;
use App\Models\DetailOrder;
use App\Models\Transaksi;
use App\Models\Cart;
use App\Models\Wishlist;
use App\Models\HomeContent;

class DashboardController extends Controller
{
    public function index()
    {
        $viewData = [
            'title' => 'Home',
            'products' => Produk::all(),
            'homeContents' => HomeContent::all(),
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
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat memperbarui profil.');
        }
    }

    public function review(string $id)
    {
        $viewData = [
            'title' => 'Review',
            'product' => Produk::find($id),
            'categories' => Kategori::all(),
        ];

        return view('review', $viewData);
    }

    public function storeReview(Request $request)
    {
        $validatedData = $request->validate([
            'id_product' => 'required|exists:produks,id',
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            Review::create([
                'id_product' => $validatedData['id_product'],
                'id_user' => auth()->user()->id,
                'rating' => $validatedData['rating'],
                'review' => $validatedData['review'],
            ]);

            DB::commit();
            return redirect()->route('products.detail', $request->id_product)->with('success', 'Review berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat menyimpan review.');
        }
    }

    public function reviewUpdate(Request $request)
    {
        $validatedData = $request->validate([
            'id' => 'required|exists:reviews,id',
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            $review = Review::where('id', $validatedData['id'])
                ->where('id_user', auth()->user()->id)
                ->first();

            if (!$review) {
                return back()->with('error', 'Review tidak ditemukan.');
            }

            $review->update([
                'rating' => $validatedData['rating'],
                'review' => $validatedData['review'],
            ]);

            DB::commit();
            return redirect()->route('products.detail', $review->id_product)->with('success', 'Review berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat memperbarui review.');
        }
    }

    public function reviewEdit(string $id)
    {
        $viewData = [
            'title' => 'Review',
            'product' => Produk::find($id),
            'categories' => Kategori::all(),
            'review' => Review::where('id_product', $id)->where('id_user', auth()->user()->id)->first(),
        ];

        return view('review-edit', $viewData);
    }

    public function wishlists()
    {
        $viewData = [
            'title' => 'Wishlists',
            'wishlists' => Wishlist::where('id_user', auth()->user()->id)->latest()->paginate(),
            'categories' => Kategori::all(),
        ];

        return view('wishlists', $viewData);
    }

    public function addWishlists(Request $request)
    {
        try {
            DB::beginTransaction();

            $wishlist = Wishlist::where('id_user', auth()->user()->id)->where('id_produk', $request->id_produk)->first();
            if ($wishlist == null) {
                Wishlist::create([
                    'id_user' => auth()->user()->id,
                    'id_produk' => $request->id_produk,
                ]);
            }

            DB::commit();
            return back()->with('success', 'Produk berhasil ditambahkan ke wishlist.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat menambahkan produk ke wishlist.');
        }
    }

    public function removeWishlists(Request $request)
    {
        try {
            DB::beginTransaction();

            $wishlist = Wishlist::where('id_user', auth()->user()->id)->where('id', $request->wishlist_id)->first();
            if ($wishlist != null) {
                $wishlist->delete();
            }

            DB::commit();
            return back()->with('success', 'Produk berhasil dihapus dari wishlist.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat menghapus produk dari wishlist.');
        }
    }

    public function about()
    {
        $viewData = [
            'title' => 'About',
            'homeContents' => HomeContent::all(),
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
        $isReview = null;
        if (auth()->user()) {
            $isReview = Review::where('id_product', $id)->where('id_user', auth()->user()->id)->first();
        }
        $viewData = [
            'title' => 'Products',
            'product' => Produk::find($id),
            'id' => 0,
            'categories' => Kategori::all(),
            'reviews' => Review::where('id_product', $id)->latest()->get(),
            'isReview' => $isReview,
        ];

        return view('product-detail', $viewData);
    }

    public function addToCart(Request $request)
    {
        try {
            DB::beginTransaction();

            $cart = Cart::where('id_user', auth()->user()->id)->where('id_produk', $request->id_produk)->first();
            $product = Produk::find($request->id_produk);
            if (!$product || $product->stok < $request->qty) {
                DB::rollBack();
                return back()->with('error', 'Stok produk tidak mencukupi.');
            }
            if ($cart == null) {
                Cart::create([
                    'id_user' => auth()->user()->id,
                    'id_produk' => $request->id_produk,
                    'qty' => $request->qty,
                ]);
            } else {
                $cart->update([
                    'qty' => $cart->qty + $request->qty,
                ]);
            }

            DB::commit();
            return back()->with('success', 'Produk berhasil ditambahkan ke keranjang.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat menambahkan produk ke keranjang.');
        }
    }

    public function checkoutCart(Request $request)
    {
        $items = json_decode($request->selected_products, true);

        if (!$items || count($items) === 0) {
            return back()->with('error', 'No items selected for checkout.');
        }

        foreach ($items as $item) {
            $idProduk = $item['id'];
            $qty = $item['qty'];
            $totalHarga = $item['harga'];

            // Simpan ke tabel transaksi, buat invoice, atau proses sesuai kebutuhanmu
            // Misalnya:
            // OrderDetail::create([
            //     'order_id' => $order->id,
            //     'product_id' => $idProduk,
            //     'qty' => $qty,
            //     'total_price' => $totalHarga,
            // ]);
        }

        return redirect()->route('checkout.success')->with('success', 'Checkout berhasil!');
    }

    public function removeFromCart(string $id)
    {
        try {
            DB::beginTransaction();

            $cart = Cart::where('id_user', auth()->user()->id)->where('id_produk', $id)->first();
            if ($cart != null) {
                $cart->delete();
            }

            DB::commit();
            return back()->with('success', 'Produk berhasil dihapus dari keranjang.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat menghapus produk dari keranjang.');
        }
    }

    public function cart()
    {
        $viewData = [
            'title' => 'Keranjang',
            // 'order' => Order::with('detailOrder')->where('id_user', auth()->user()->id)->where('status', 'pending')->latest()->first(),
            'cart' => Cart::where('id_user', auth()->user()->id)->latest()->get(),
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

    public function addToOrderAndCheckout(Request $request)
    {
        try {
            DB::beginTransaction();

            $order = Order::where('id_user', auth()->user()->id)->where('status', 'pending')->latest()->first();
            $product = Produk::find($request->id_produk);
            if (!$product || $product->stok < $request->qty) {
                return back()->with('error', 'Stok produk tidak mencukupi.');
            }

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
                    'total' => $order->total + ($request->qty * $request->harga),
                ]);
            } else {
                $orderDetail->update([
                    'qty' => $orderDetail->qty + $request->qty,
                ]);
                $order->update([
                    'total' => $order->total + ($request->qty * $request->harga),
                ]);
            }

            $invoiceId = 'INV-' . date('Ymd') . '-' . str_pad($order->id, 5, '0', STR_PAD_LEFT);

            $transaksi = Transaksi::create([
                'id_order' => $order->id,
                'invoice_id' => $invoiceId,
                'total_qty_item' => $request->qty,
                'total_bayar' => $order->total,
                'bukti_pembayaran' => 'pending',
                'status_pembayaran' => 'pending',
            ]);

            $order->update([
                'status' => 'checkout',
            ]);

            DB::commit();
            return redirect()->route('cart.upload.payment', $transaksi->id)->with('success', 'Produk berhasil ditambahkan dan checkout berhasil.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat menambahkan produk dan melakukan checkout.');
        }
    }

    public function checkoutMultiples(Request $request)
    {
        try {
            DB::beginTransaction();

            $validatedData = $request->validate([
                'total_qty_item' => 'required|integer|min:1',
                'total_bayar' => 'required|integer|min:1',
                'selected_products' => 'required|string', // JSON string dari JS
            ]);

            $selectedProducts = json_decode($request->selected_products, true);

            if (empty($selectedProducts)) {
                return back()->with('error', 'Tidak ada produk yang dipilih untuk checkout.');
            }

            // Buat order jika belum ada
            $order = Order::where('id_user', auth()->user()->id)
                ->where('status', 'pending')
                ->latest()
                ->first();

            if ($order == null) {
                $order = Order::create([
                    'id_user' => auth()->user()->id,
                    'total' => 0,
                    'status' => 'pending',
                ]);
            }

            // Tambahkan produk ke order
            foreach ($selectedProducts as $item) {
                $orderDetail = DetailOrder::where('id_order', $order->id)
                    ->where('id_produk', $item['id'])
                    ->first();

                if ($orderDetail == null) {
                    DetailOrder::create([
                        'id_order' => $order->id,
                        'id_produk' => $item['id'],
                        'harga' => $item['harga'],
                        'qty' => $item['qty'],
                    ]);
                } else {
                    $orderDetail->update([
                        'qty' => $orderDetail->qty + $item['qty'],
                    ]);
                }

                $order->update([
                    'total' => $order->total + ($item['qty'] * $item['harga']),
                ]);
            }

            // Hapus produk yang dipilih dari keranjang
            foreach ($selectedProducts as $item) {
                Cart::where('id_user', auth()->user()->id)
                    ->where('id_produk', $item['id'])
                    ->delete();
            }

            // Buat ID invoice
            $invoiceId = 'INV-' . date('Ymd') . '-' . str_pad($order->id, 5, '0', STR_PAD_LEFT);

            // Buat transaksi
            $transaksi = Transaksi::create([
                'id_order' => $order->id,
                'invoice_id' => $invoiceId,
                'total_qty_item' => $validatedData['total_qty_item'],
                'total_bayar' => $validatedData['total_bayar'],
                'bukti_pembayaran' => 'pending',
                'status_pembayaran' => 'pending',
            ]);

            // Tandai order sebagai checkout
            $order->update(['status' => 'checkout']);

            DB::commit();

            return redirect()->route('cart.upload.payment', $transaksi->id)->with('success', 'Checkout berhasil.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat melakukan checkout: ' . $e->getMessage());
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
            $products = $transaksi->order->detailOrder;

            foreach ($products as $detail) {
                $produk = Produk::find($detail->id_produk);
                if ($produk) {
                    $produk->stok = max(0, $produk->stok - $detail->qty);
                    $produk->save();
                }
            }

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
