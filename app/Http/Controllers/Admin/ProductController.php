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
use App\Models\ProdukPhoto;

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
            'foto_produk' => 'nullable|array',
            'foto_produk.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'sizes' => 'required|array',
            'sizes.*.size' => 'required|string|max:50',
            'sizes.*.stock' => 'required|integer|min:0',
        ]);


        DB::beginTransaction();
        try {
            // Remove sizes from validatedData before creating Produk
            $sizes = $validatedData['sizes'];
            $fotoFiles = $request->file('foto_produk') ?? [];
            unset($validatedData['sizes']);
            unset($validatedData['foto_produk']);

            // Sum stock from all sizes
            $totalStock = array_sum(array_column($sizes, 'stock'));
            $validatedData['stok'] = $totalStock;

            // Create Produk
            $produk = Produk::create($validatedData);

            // Store multiple photos
            foreach ($fotoFiles as $file) {
                $path = $file->store('produk_images', 'public');
                ProdukPhoto::create([
                    'id_produk' => $produk->id,
                    'url' => $path,
                ]);
            }

            // Insert sizes into produkSize table
            foreach ($sizes as $sizeData) {
                ProdukSize::create([
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
            'foto_produk' => 'nullable|array',
            'foto_produk.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'sizes' => 'required|array',
            'sizes.*.size' => 'required|string|max:50',
            'sizes.*.stock' => 'required|integer|min:0',
        ]);

        DB::beginTransaction();
        try {
            $produk = Produk::findOrFail($validatedData['id']);

            // Store new photos without deleting old ones
            if ($request->hasFile('foto_produk')) {
                $fotoFiles = $request->file('foto_produk');
                foreach ($fotoFiles as $file) {
                    $path = $file->store('produk_images', 'public');
                    ProdukPhoto::create([
                        'id_produk' => $produk->id,
                        'url' => $path,
                    ]);
                }
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

            // Delete related photos from ProdukPhoto
            $photos = ProdukPhoto::where('id_produk', $produk->id)->get();
            foreach ($photos as $photo) {
                if ($photo->url && Storage::disk('public')->exists($photo->url)) {
                    Storage::disk('public')->delete($photo->url);
                }
                $photo->delete();
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

    public function deleteImage(Request $request)
    {
        $validatedData = $request->validate([
            'id' => 'required|integer',
        ]);


        DB::beginTransaction();
        try {
            $photo = ProdukPhoto::findOrFail($validatedData['id']);

            // Delete the photo file if it exists
            if ($photo->url && Storage::disk('public')->exists($photo->url)) {
                Storage::disk('public')->delete($photo->url);
            }

            $photo->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Foto produk berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
