<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TrackingController extends Controller
{
    /**
     * Display order delivery tracking page.
     */
    public function index(Request $request)
    {
        $orderNumber = $request->query('order_number') ?: session('last_order_number');
        
        $order = null;
        $userOrders = collect();

        if (Auth::check()) {
            $userOrders = Order::where('user_id', Auth::id())
                ->whereNotIn('status', ['cancelled', 'delivered', 'refund_requested', 'refunded'])
                ->latest()
                ->get();
        }

        if ($orderNumber) {
            $order = Order::with('items')
                ->where('order_number', $orderNumber)
                ->whereNotIn('status', ['cancelled', 'refund_requested', 'refunded'])
                ->first();
        } elseif ($userOrders->isNotEmpty()) {
            $order = $userOrders->first();
        }

        $currentStep = 0;
        $progressWidth = '0%';
        
        if ($order) {
            $statusMap = [
                'pending' => 1,
                'processed' => 2,
                'shipped' => 3,
                'enroute' => 4,
                'arrived' => 5,
                'delivered' => 5,
            ];

            $currentStep = $statusMap[$order->status] ?? 1;
            
            $progressMap = [
                1 => '8%',
                2 => '29%',
                3 => '50%',
                4 => '71%',
                5 => '100%',
            ];
            $progressWidth = $progressMap[$currentStep] ?? '8%';
        }

        return view('Pages.delivery', compact('order', 'userOrders', 'currentStep', 'progressWidth', 'orderNumber'));
    }

    /**
     * Search for an order by order number.
     */
    public function search(Request $request)
    {
        $request->validate([
            'order_number' => 'required|string|max:50',
        ]);

        $orderNumber = trim($request->order_number);
        
        $orderExists = Order::where('order_number', $orderNumber)
            ->whereNotIn('status', ['cancelled', 'refund_requested', 'refunded'])
            ->exists();

        if (!$orderExists) {
            return redirect()->back()
                ->with('error', 'Order number ' . $orderNumber . ' was not found or has been completed. Please check and try again.')
                ->withInput();
        }

        return redirect()->route('delivery.track', ['order_number' => $orderNumber]);
    }

    /**
     * Cancel the order.
     */
    public function cancel(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        if (in_array($order->status, ['delivered', 'cancelled'])) {
            return redirect()->back()->with('error', 'Delivered or already cancelled orders cannot be cancelled.');
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

        // Clear session tracking for this order if active
        if (session('last_order_number') === $order->order_number) {
            session()->forget('last_order_number');
        }

        // Delete the order to completely remove it from the system
        $order->delete();

        return redirect()->route('delivery.track')->with('success', 'Your order has been successfully cancelled and removed.');
    }

    /**
     * Request return/refund for the order.
     */
    public function requestRefund(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        if ($order->status !== 'delivered') {
            return redirect()->back()->with('error', 'Only delivered orders can be returned/refunded.');
        }

        $order->update([
            'status' => 'refund_requested'
        ]);

        return redirect()->back()->with('success', 'Your return/refund request has been successfully submitted and is waiting for administrator approval.');
    }
}
