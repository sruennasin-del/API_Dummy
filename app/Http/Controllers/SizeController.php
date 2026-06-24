<?php

namespace App\Http\Controllers;

use App\Models\Size;
use Illuminate\Http\Request;

class SizeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Size::query();

        // Handle search
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where('name', 'like', "%{$search}%");
        }

        // Handle status filter
        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        $sizes = $query->latest()->paginate(10)->withQueryString();

        return view('admin.sizes.index', compact('sizes'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Size $size)
    {
        return redirect('/admin/sizes/' . $size->id . '/edit');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.sizes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        Size::create($request->only(['name', 'status']));

        return redirect('/admin/sizes')->with('success', 'Size created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Size $size)
    {
        return view('admin.sizes.edit', compact('size'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Size $size)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        $size->update($request->only(['name', 'status']));

        return redirect('/admin/sizes')->with('success', 'Size updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Size $size)
    {
        $size->delete();

        return redirect('/admin/sizes')->with('success', 'Size deleted successfully.');
    }
}
