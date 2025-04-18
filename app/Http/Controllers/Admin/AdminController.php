<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\User;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $viewData = [
            'title' => 'Admin Management',
            'datas' => User::where('role', 'admin')->paginate(10),
        ];

        return view('admin.admin.index', $viewData);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $viewData = [
            'title' => 'Create Admin',
        ];

        return view('admin.admin.create', $viewData);
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
        $validatedData['role'] = 'admin';

        try {
            DB::beginTransaction();

            $validatedData['password'] = bcrypt($validatedData['password']);
            User::create($validatedData);

            DB::commit();
            return redirect()->route('admin.admin.index')->with('success', 'Admin created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Failed to create admin: ' . $e->getMessage()]);
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
            'title' => 'Edit Admin',
            'data' => User::findOrFail($id),
        ];

        return view('admin.admin.edit', $viewData);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $request->id,
            'password' => 'nullable|string|min:8',
        ]);

        try {
            DB::beginTransaction();

            $user = User::findOrFail($validatedData['id']);
            $user->name = $validatedData['name'];
            $user->username = $validatedData['username'];
            $user->email = $validatedData['email'];

            if (!empty($validatedData['password'])) {
                $user->password = bcrypt($validatedData['password']);
            }

            $user->save();

            DB::commit();
            return redirect()->route('admin.admin.index')->with('success', 'Admin updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Failed to update admin: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $validatedData = $request->validate([
            'id' => 'required|exists:users,id',
        ]);

        try {
            DB::beginTransaction();

            User::destroy($validatedData['id']);

            DB::commit();
            return redirect()->route('admin.admin.index')->with('success', 'Admin deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Failed to delete admin: ' . $e->getMessage()]);
        }
    }
}
