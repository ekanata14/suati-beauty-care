<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// Models
use App\Models\Produk;
use App\Models\Kategori;

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
            'products' => Produk::all(),
            'id' => 0,
            'categories' => Kategori::all(),
        ];

        return view('products', $viewData);
    }

    public function productsCategory($id)
    {
        $viewData = [
            'title' => 'Products',
            'products' => Produk::where('id_kategori', $id)->get(),
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
            })->paginate(10);
        }
        $viewData = [
            'title' => 'Products',
            'products' => $products,
            'id' => 0,
            'categories' => Kategori::all(),
        ];

        return view("products", $viewData);
    }
}
