<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

// Models
use App\Models\User;
use App\Models\Konsumen;

class PelangganController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $viewData = [
            'title' => 'Pelanggan Management',
            'datas' => User::where('role', 'pelanggan')->paginate(10),
        ];

        return view('admin.pelanggan.index', $viewData);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $viewData = [
            'title' => 'Create Pelanggan',
        ];

        return view('admin.pelanggan.create', $viewData);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $konsumenData = $request->validate([
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|string',
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'telepon' => 'required|string|max:15',
        ]);

        $validatedData['role'] = 'pelanggan';

        try {
            DB::beginTransaction();

            if ($request->hasFile('foto_profil')) {
                $konsumenData['foto_profil'] = $request->file('foto_profil')->store('foto_profil', 'public');
            }

            $validatedData['password'] = bcrypt($validatedData['password']);
            $user = User::create($validatedData);

            $konsumenData['id_user'] = $user->id;
            $konsumen = Konsumen::create($konsumenData);

            DB::commit();
            return redirect()->route('admin.pelanggan.index')->with('success', 'Pelanggan created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Failed to create pelanggan: ' . $e->getMessage()]);
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
            'title' => 'Edit Pelanggan',
            'data' => User::findOrFail($id),
        ];

        return view('admin.pelanggan.edit', $viewData);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $id = $request->id;

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'email' => "required|string|email|max:255|unique:users,email,{$request->id}",
            'password' => 'nullable|string|min:8',
        ]);

        $konsumenData = $request->validate([
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|string',
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'telepon' => 'required|string|max:15',
        ]);

        try {
            DB::beginTransaction();

            $user = User::findOrFail($request->id);

            if ($request->filled('password')) {
                $validatedData['password'] = bcrypt($validatedData['password']);
            } else {
                unset($validatedData['password']);
            }

            $user->update($validatedData);

            $konsumen = Konsumen::where('id_user', $user->id)->firstOrFail();

            if ($request->hasFile('foto_profil')) {
                if ($konsumen->foto_profil) {
                    // Delete the old profile picture
                    Storage::disk('public')->delete($konsumen->foto_profil);
                }
                $konsumenData['foto_profil'] = $request->file('foto_profil')->store('foto_profil', 'public');
            }

            $konsumen->update($konsumenData);

            DB::commit();
            return redirect()->route('admin.pelanggan.index')->with('success', 'Pelanggan updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Failed to update pelanggan: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */ public function destroy(Request $request)
    {
        $validatedData = $request->validate([
            'id' => 'required|exists:users,id',
        ]);

        try {
            DB::beginTransaction();

            $user = User::findOrFail($validatedData['id']);

            // Delete the associated Konsumen record
            $konsumen = Konsumen::where('id_user', $user->id)->first();

            if ($konsumen->foto_profil) {
                // Delete the profile picture if it exists
                Storage::disk('public')->delete($user->Konsumen->foto_profil);
            }

            // Delete Konsumen Record Data
            if ($konsumen) {
                $konsumen->delete();
            }

            // Delete User
            User::destroy($validatedData['id']);

            DB::commit();
            return redirect()->route('admin.pelanggan.index')->with('success', 'Pelanggan deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Failed to delete admin: ' . $e->getMessage()]);
        }
    }

    public function logHistory($id)
    {
        // Cari data pengiriman
        $user = User::findOrFail($id);

        // Ambil log aktivitas dari Spatie (otomatis terurut dari terbaru)
        $activities = $user->activities()->with('causer')->get();

        $viewData = [
            'title' => 'Log History for ' . $user->name,
            'item' => $user,
            'activities' => $activities,
        ];

        return view('admin.log', $viewData);
    }
}
