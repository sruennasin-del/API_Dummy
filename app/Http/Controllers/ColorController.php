<?php

namespace App\Http\Controllers;

use App\Models\Color;
use Illuminate\Http\Request;

class ColorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Color::query();
            
        // Handle search
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        // Handle status filter
        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        $colors = $query->latest()->paginate(10)->withQueryString();

        return view('admin.colors.index', compact('colors'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Color $color)
    {
        return redirect('/admin/colors/' . $color->id . '/edit');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.colors.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => ['required', 'string', 'max:7', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
            'status' => 'required|in:active,inactive',
        ], [
            'code.regex' => 'The color code must be a valid HEX color (e.g. #FF0000).',
        ]);

        Color::create($request->only(['name', 'code', 'status']));

        return redirect('/admin/colors')->with('success', 'Color created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Color $color)
    {
        return view('admin.colors.edit', compact('color'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Color $color)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => ['required', 'string', 'max:7', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
            'status' => 'required|in:active,inactive',
        ], [
            'code.regex' => 'The color code must be a valid HEX color (e.g. #FF0000).',
        ]);

        $color->update($request->only(['name', 'code', 'status']));

        return redirect('/admin/colors')->with('success', 'Color updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Color $color)
    {
        $color->delete();

        return redirect('/admin/colors')->with('success', 'Color deleted successfully.');
    }
}
