<?php

namespace App\Http\Controllers;

use App\Models\BoomPromotion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BoomPromotionController extends Controller
{
    public function index()
    {
        $promotions = BoomPromotion::paginate(10);
        return view('admin.boom_promotions.index', compact('promotions'));
    }

    public function create()
    {
        return view('admin.boom_promotions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'    => 'required|string|max:255',
            'image'    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'shape'    => 'required|in:starburst,circle,heart,square',
            'status'   => 'required|in:active,inactive',
        ]);

        $data = $request->only([
            'title', 'subtitle', 'description', 'shape', 'link_url', 'status'
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('promotions', 'public');
        }

        BoomPromotion::create($data);
        return redirect('/admin/boom-promotions')->with('success', 'Promotion created successfully.');
    }

    public function edit($id)
    {
        $promotion = BoomPromotion::findOrFail($id);
        return view('admin.boom_promotions.edit', compact('promotion'));
    }

    public function update(Request $request, $id)
    {
        $promotion = BoomPromotion::findOrFail($id);
        
        $request->validate([
            'title'  => 'required|string|max:255',
            'image'  => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'shape'  => 'required|in:starburst,circle,heart,square',
            'status' => 'required|in:active,inactive',
        ]);

        $data = $request->only([
            'title', 'subtitle', 'description', 'shape', 'link_url', 'status'
        ]);

        if ($request->hasFile('image')) {
            if ($promotion->image) Storage::disk('public')->delete($promotion->image);
            $data['image'] = $request->file('image')->store('promotions', 'public');
        }

        $promotion->update($data);
        return redirect('/admin/boom-promotions')->with('success', 'Promotion updated successfully.');
    }

    public function destroy($id)
    {
        $promotion = BoomPromotion::findOrFail($id);
        if ($promotion->image) Storage::disk('public')->delete($promotion->image);
        $promotion->delete();
        return redirect('/admin/boom-promotions')->with('success', 'Promotion deleted.');
    }
}
