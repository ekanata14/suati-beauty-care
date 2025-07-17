<?php

namespace App\Http\Controllers;

use App\Models\HomeContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeContentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $viewData = [
            'title' => 'Home Content Management',
            'data' => HomeContent::find(1),
        ];

        return view('admin.home-content.index', $viewData);
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
    public function show(HomeContent $homeContent)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(HomeContent $homeContent)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        try {
            DB::beginTransaction();

            $validatedData = $request->validate([
                'id' => 'required|exists:home_contents,id',
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            ]);

            $homeContent = HomeContent::findOrFail($validatedData['id']);
            $homeContent->title = $validatedData['title'];
            $homeContent->description = $validatedData['description'];

            if ($request->hasFile('logo')) {
                $file = $request->file('logo');
                $path = 'storage/' . $file->store('home_content_images', 'public');
                $homeContent->logo = $path;
            }

            $homeContent->save();

            DB::commit();

            return redirect()->route('admin.home-content.index')->with('success', 'Home content updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Failed to update home content: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(HomeContent $homeContent)
    {
        //
    }
}
