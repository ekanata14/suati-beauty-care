<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $viewData = [
            'title' => 'Review Management',
            'datas' => Review::paginate(10),
        ];

        return view('admin.review.index', $viewData);
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
    public function show(Review $review)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $review = Review::findOrFail($id);
        $viewData = [
            'title' => 'Edit Review',
            'review' => $review,
        ];

        return view('admin.review.edit', $viewData);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'id' => 'required|exists:reviews,id',
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string|max:255',
        ]);

        $review = Review::find($validatedData['id']);

        try {
            DB::beginTransaction();

            $review = Review::where('id', $validatedData['id'])
                ->where('id_user', $review->user->id)
                ->first();

            if (!$review) {
                return back()->with('error', 'Review tidak ditemukan.');
            }

            $review->update([
                'rating' => $validatedData['rating'],
                'review' => $validatedData['review'],
            ]);

            DB::commit();
            return redirect()->route('admin.review.index', $review->product->id)->with('success', 'Review berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat memperbarui review.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $review = Review::find($request->id);
        $review->delete();

        return redirect()->route('admin.review.index')->with('success', 'Review deleted successfully');
    }
}
