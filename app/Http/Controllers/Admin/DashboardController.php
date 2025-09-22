<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

// Models
use App\Models\User;
use App\Models\Produk;
use App\Models\Order;
use App\Models\Transaksi;

class DashboardController extends Controller
{
    public function index()
    {
        $viewData = [
            'title' => 'Admin Dashboard',
            'dashboardData' => [
                'transactionSummary' => [
                    'paid' => Transaksi::where('status_pembayaran', 'paid')->count(),
                    'pending' => Transaksi::where('status_pembayaran', 'pending')->count(),
                    'waiting' => Transaksi::where('status_pembayaran', 'waiting')->count(),
                    'total' => Transaksi::whereIn('status_pembayaran', ['paid', 'pending', 'waiting'])->count(),
                    'link' => route('admin.transaction.index'),
                ],
                'totalUsers' => User::count(),
                'users' => [
                    'count' => User::count(),
                    'link' => route('admin.pelanggan.index'),
                ],
                'products' => [
                    'count' => Produk::count(),
                    'link' => route('admin.product.index'),
                ],
                'orders' => [
                    'count' => Transaksi::count(),
                    'link' => route('admin.transaction.index'),
                ],
                'recentOrders' => [
                    'data' => Transaksi::orderBy('created_at', 'desc')->take(5)->get(),
                    'link' => route('admin.transaction.index'),
                ],
            ]
        ];

        return view('admin.dashboard', $viewData);
    }

    public function updateProfile(Request $request)
    {
        try {
            DB::beginTransaction();

            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'username' => 'required|string|max:255|unique:users,username,' . auth()->user()->id,
                'email' => 'required|email|max:255|unique:users,email,' . auth()->user()->id,
            ]);

            $user = auth()->user();
            $user->update([
                'name' => $validatedData['name'],
                'username' => $validatedData['username'],
                'email' => $validatedData['email'],
            ]);

            DB::commit();
            return back()->with('success', 'Profil berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat memperbarui profil.');
        }
    }
}
