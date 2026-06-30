<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    /**
     * Display the stock inventory management dashboard.
     */
    public function index(Request $request)
    {
        // 1. Calculate stock statistics (based on all products)
        $stats = [
            'total' => Product::count(),
            'in_stock' => Product::where('status', 'active')->where('stock', '>', 5)->count(),
            'low_stock' => Product::where('status', 'active')->whereBetween('stock', [1, 5])->count(),
            'out_of_stock' => Product::where('status', 'active')->where('stock', 0)->count(),
            'inactive' => Product::where('status', 'inactive')->count(),
        ];

        // 2. Fetch products with filters
        $query = Product::with('category');

        // Handle Search
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('id', 'like', "%{$search}%");
            });
        }

        // Handle Category Filter
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->get('category_id'));
        }

        // Handle Stock Status Filter
        if ($request->filled('status')) {
            $status = $request->get('status');
            if ($status === 'In Stock') {
                $query->where('status', 'active')->where('stock', '>', 5);
            } elseif ($status === 'Low Stock') {
                $query->where('status', 'active')->whereBetween('stock', [1, 5]);
            } elseif ($status === 'Out of Stock') {
                $query->where('status', 'active')->where('stock', 0);
            } elseif ($status === 'inactive') {
                $query->where('status', 'inactive');
            }
        }

        $products = $query->latest('updated_at')->paginate(15)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('admin.inventory.index', compact('products', 'categories', 'stats'));
    }

    /**
     * Update stock level for a single product (via AJAX).
     */
    public function updateStock(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:ec_products,id',
            'stock' => 'required|integer|min:0',
        ]);

        $product = Product::findOrFail($request->product_id);
        $product->stock = $request->stock;
        $product->save();

        // Determine stock status label/class to send back for instant UI update
        $statusBadge = '';
        if ($product->status === 'inactive') {
            $statusBadge = '<span class="badge-premium badge-premium-danger"><i class="ti ti-circle-x-filled"></i> Inactive (Disabled)</span>';
        } elseif ($product->stock === 0) {
            $statusBadge = '<span class="badge-premium badge-premium-danger"><i class="ti ti-circle-x-filled"></i> Out of Stock</span>';
        } elseif ($product->stock <= 5) {
            $statusBadge = '<span class="badge-premium badge-premium-warning"><i class="ti ti-alert-triangle-filled"></i> Low Stock (' . $product->stock . ')</span>';
        } else {
            $statusBadge = '<span class="badge-premium badge-premium-success"><i class="ti ti-circle-check-filled"></i> In Stock (' . $product->stock . ')</span>';
        }

        return response()->json([
            'success' => true,
            'message' => 'Stock updated successfully.',
            'stock' => $product->stock,
            'badge' => $statusBadge,
            'updated_at' => $product->updated_at->diffForHumans(),
        ]);
    }

    /**
     * Perform bulk updates on selected products.
     */
    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'product_ids' => 'required|array',
            'product_ids.*' => 'exists:ec_products,id',
            'action_type' => 'required|in:set,increase,decrease',
            'value' => 'required|integer|min:0',
        ]);

        $products = Product::whereIn('id', $request->product_ids)->get();
        $updatedCount = 0;

        foreach ($products as $product) {
            if ($request->action_type === 'set') {
                $product->stock = $request->value;
            } elseif ($request->action_type === 'increase') {
                $product->stock += $request->value;
            } elseif ($request->action_type === 'decrease') {
                $product->stock = max(0, $product->stock - $request->value);
            }

            if ($product->isDirty('stock')) {
                $product->save();
                $updatedCount++;
            }
        }

        return redirect()->back()->with('success', "Successfully updated stock for {$updatedCount} products.");
    }
}
