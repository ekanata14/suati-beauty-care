<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

// Models
use App\Models\Produk;
use App\Models\ProdukSize;
use App\Models\Kategori;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $viewData = [
            'title' => 'Produk Management',
            'datas' => Produk::paginate(10),
        ];

        return view('admin.product.index', $viewData);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $viewData = [
            'title' => 'Create Produk',
            'categories' => Kategori::all(),
        ];
        return view('admin.product.create', $viewData);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'harga' => 'required',
            'id_kategori' => 'required',
            'deskripsi' => 'required|string',
            'foto_produk' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'sizes' => 'required|array',
            'sizes.*.size' => 'required|string|max:50',
            'sizes.*.stock' => 'required|integer|min:0',
        ]);

        DB::beginTransaction();
        try {
            if ($request->hasFile('foto_produk')) {
                $file = $request->file('foto_produk');
                $path = $file->store('produk_images', 'public');
                $validatedData['foto_produk'] = $path;
            }

            // Remove sizes from validatedData before creating Produk
            $sizes = $validatedData['sizes'];
            unset($validatedData['sizes']);

            // Sum stock from all sizes
            $totalStock = array_sum(array_column($sizes, 'stock'));
            $validatedData['stok'] = $totalStock;

            // Create Produk
            $produk = Produk::create($validatedData);

            // Insert sizes into produkSize table
            foreach ($sizes as $sizeData) {
                $produkSize = ProdukSize::create([
                    'id_produk' => $produk->id,
                    'size' => $sizeData['size'],
                    'stock' => $sizeData['stock'],
                ]);
            }

            DB::commit();

            return redirect()->route('admin.product.index')->with('success', 'Produk berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $viewData = [
            'title' => 'Edit Produk',
            'data' => Produk::findOrFail($id),
            'categories' => Kategori::all(),
        ];

        return view('admin.product.edit', $viewData);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'id' => 'required|integer',
            'nama' => 'required|string|max:255',
            'harga' => 'required',
            'id_kategori' => 'required',
            'deskripsi' => 'required|string',
            'foto_produk' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'sizes' => 'required|array',
            'sizes.*.size' => 'required|string|max:50',
            'sizes.*.stock' => 'required|integer|min:0',
        ]);

        DB::beginTransaction();
        try {
            $produk = Produk::findOrFail($validatedData['id']);

            if ($request->hasFile('foto_produk')) {
                // Delete the old photo if it exists
                if ($produk->foto_produk && Storage::disk('public')->exists($produk->foto_produk)) {
                    Storage::disk('public')->delete($produk->foto_produk);
                }

                // Store the new photo
                $file = $request->file('foto_produk');
                $path = $file->store('produk_images', 'public');
                $validatedData['foto_produk'] = $path;
            }

            // Remove sizes from validatedData before updating Produk
            $sizes = $validatedData['sizes'];
            unset($validatedData['sizes']);

            // Sum stock from all sizes
            $totalStock = array_sum(array_column($sizes, 'stock'));
            $validatedData['stok'] = $totalStock;

            // Update Produk
            $produk->update($validatedData);

            // Delete existing sizes and insert new ones
            ProdukSize::where('id_produk', $produk->id)->delete();
            foreach ($sizes as $sizeData) {
                ProdukSize::create([
                    'id_produk' => $produk->id,
                    'size' => $sizeData['size'],
                    'stock' => $sizeData['stock'],
                ]);
            }

            DB::commit();

            return redirect()->route('admin.product.index')->with('success', 'Produk berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $validatedData = $request->validate([
            'id' => 'required|integer',
        ]);

        DB::beginTransaction();
        try {
            $produk = Produk::findOrFail($request->id);

            // Delete the photo if it exists
            if ($produk->foto_produk && Storage::disk('public')->exists($produk->foto_produk)) {
                Storage::disk('public')->delete($produk->foto_produk);
            }

            // Delete related sizes
            ProdukSize::where('id_produk', $produk->id)->delete();

            $produk->delete();

            DB::commit();

            return redirect()->route('admin.product.index')->with('success', 'Produk berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }
}
