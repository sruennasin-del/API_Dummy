<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    /**
     * Display a listing of orders.
     */
    public function index(Request $request)
    {
        $query = Order::query();

        // Search by order number, customer name, customer email
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_email', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        $orders = $query->latest()->paginate(10)->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Show the order details / edit page.
     */
    public function edit(Order $order)
    {
        $order->load('items');
        return view('admin.orders.edit', compact('order'));
    }

    /**
     * Update the order status and tracking details.
     */
    public function update(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|string|in:pending,processed,shipped,enroute,arrived,delivered,cancelled',
            'courier' => 'nullable|string|max:100',
            'tracking_number' => 'nullable|string|max:100',
            'eta' => 'nullable|string|max:100',
        ]);

        $oldStatus = $order->status;
        $newStatus = $request->status;

        if ($newStatus === 'cancelled' && $oldStatus !== 'cancelled') {
            // Restore stock and decrement sales
            foreach ($order->items as $item) {
                if ($item->product_id) {
                    $product = \App\Models\Product::find($item->product_id);
                    if ($product) {
                        $product->increment('stock', $item->qty);
                        $product->decrement('sales', $item->qty);
                    }
                }
            }
        } elseif ($oldStatus === 'cancelled' && $newStatus !== 'cancelled') {
            // Re-deduct stock and increment sales if moving away from cancelled status
            foreach ($order->items as $item) {
                if ($item->product_id) {
                    $product = \App\Models\Product::find($item->product_id);
                    if ($product) {
                        $product->decrement('stock', $item->qty);
                        $product->increment('sales', $item->qty);
                    }
                }
            }
        }

        $order->update([
            'status' => $newStatus,
            'courier' => $request->courier,
            'tracking_number' => $request->tracking_number,
            'eta' => $request->eta,
        ]);

        return redirect('/admin/orders')->with('success', 'Order ' . $order->order_number . ' updated successfully.');
    }

    /**
     * Remove the order.
     */
    public function destroy(Order $order)
    {
        // If order was not cancelled, restore stock and decrement sales before deleting
        if ($order->status !== 'cancelled') {
            foreach ($order->items as $item) {
                if ($item->product_id) {
                    $product = \App\Models\Product::find($item->product_id);
                    if ($product) {
                        $product->increment('stock', $item->qty);
                        $product->decrement('sales', $item->qty);
                    }
                }
            }
        }

        $order->delete();
        return redirect('/admin/orders')->with('success', 'Order deleted successfully.');
    }
}
