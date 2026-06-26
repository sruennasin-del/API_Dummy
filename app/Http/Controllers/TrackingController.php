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
            $userOrders = Order::where('user_id', Auth::id())->latest()->get();
        }

        if ($orderNumber) {
            $order = Order::with('items')->where('order_number', $orderNumber)->first();
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
        
        $orderExists = Order::where('order_number', $orderNumber)->exists();

        if (!$orderExists) {
            return redirect()->back()
                ->with('error', 'Order number ' . $orderNumber . ' was not found. Please check and try again.')
                ->withInput();
        }

        return redirect()->route('delivery.track', ['order_number' => $orderNumber]);
    }
}
