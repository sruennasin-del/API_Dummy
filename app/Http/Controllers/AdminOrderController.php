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
            'status' => 'required|string|in:pending,processed,shipped,enroute,arrived,delivered,cancelled,refund_requested,refunded',
            'courier' => 'nullable|string|max:100',
            'tracking_number' => 'nullable|string|max:100',
            'eta' => 'nullable|string|max:100',
        ]);

        $oldStatus = $order->status;
        $newStatus = $request->status;

        $restoresStock = ['cancelled', 'refunded'];

        if (in_array($newStatus, $restoresStock) && !in_array($oldStatus, $restoresStock)) {
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
        } elseif (!in_array($newStatus, $restoresStock) && in_array($oldStatus, $restoresStock)) {
            // Re-deduct stock and increment sales if moving away from cancelled/refunded status
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
        // If order was not cancelled or refunded, restore stock and decrement sales before deleting
        if (!in_array($order->status, ['cancelled', 'refunded'])) {
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

    /**
     * Display a listing of return/refund requests.
     */
    public function returnsIndex(Request $request)
    {
        $query = Order::query()
            ->whereIn('status', ['refund_requested', 'refunded']);

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

        return view('admin.orders.returns', compact('orders'));
    }

    /**
     * Approve the refund and return items to stock.
     */
    public function acceptRefund(Order $order)
    {
        if ($order->status !== 'refund_requested') {
            return redirect()->back()->with('error', 'Only refund requests can be accepted.');
        }

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

        $order->update([
            'status' => 'refunded'
        ]);

        return redirect()->back()->with('success', 'Refund request for order ' . $order->order_number . ' has been approved. Stock has been restored.');
    }

    /**
     * Reject the refund request and restore status to delivered.
     */
    public function rejectRefund(Order $order)
    {
        if ($order->status !== 'refund_requested') {
            return redirect()->back()->with('error', 'Only refund requests can be rejected.');
        }

        $order->update([
            'status' => 'delivered'
        ]);

        return redirect()->back()->with('success', 'Refund request for order ' . $order->order_number . ' has been rejected. Order status is restored to Delivered.');
    }
}
