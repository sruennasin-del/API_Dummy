<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class CartController extends Controller
{
    //
      public function add(Request $request)
    {
        $cart = Session::get('cart', []);

      
        $id = $request->id;

        if (isset($cart[$id])) {
            $cart[$id]['qty'] += 1;
        } else {
            $cart[$id] = [
                "id" => $id,
                "title" => $request->title,
                "price" => $request->price,
                "thumbnail" => $request->thumbnail,
                "qty" => 1
            ];
        }

        Session::put('cart', $cart);

        return response()->json([
                "message" => "Added to cart successfully",
                "cart" => $cart,
                "cart_count" => count($cart)
            ]);
    }
     public function cart()
    {
        $cart = session()->get('cart', []);
        //  dd($cart); // 👈 ADD THIS FIRST

        return view('cart.cart', compact('cart'));
    }
        public function increaseQty(Request $request)
    {
        $cart = session()->get('cart', []);

        $index = $request->index;

        if(isset($cart[$index])){
            $cart[$index]['qty']++;
        }

        session()->put('cart', $cart);

        return back();
    }

    public function decreaseQty(Request $request)
    {
        $cart = session()->get('cart', []);

        $index = $request->index;

        if(isset($cart[$index])){

            if($cart[$index]['qty'] > 1){
                $cart[$index]['qty']--;
            }

        }

        session()->put('cart', $cart);

        return back();
    }
    public function remove(Request $request)
    {
        $cart = session()->get('cart', []);

        unset($cart[$request->index]);

        session()->put('cart', array_values($cart));

        return back();
    }
    public function clear()
    {
        session()->forget('cart');

        return back();
    }

    public function checkout(Request $request)
    {
        $cart = session()->get('cart', []);
        
        if (count($cart) === 0) {
            return redirect()->back()->with('error', 'Your cart is empty.');
        }

        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:50',
            'customer_address' => 'required|string|max:1000',
            'payment_method' => 'required|string|in:ABA,ACLEDA,Wing,Cash',
        ]);

        $subtotal = collect($cart)->sum(function($item){
            return ($item['price'] ?? 2.50) * $item['qty'];
        });

        $service = 1.50;
        $delivery = 2.00;
        $tax = $subtotal * 0.10;
        $grandTotal = $subtotal + $service + $delivery + $tax;

        // Generate unique order number
        do {
            $orderNumber = 'ORD-' . strtoupper(Str::random(8));
        } while (Order::where('order_number', $orderNumber)->exists());

        $order = Order::create([
            'user_id' => Auth::id(),
            'order_number' => $orderNumber,
            'customer_name' => $request->customer_name,
            'customer_email' => $request->customer_email,
            'customer_phone' => $request->customer_phone,
            'customer_address' => $request->customer_address,
            'payment_method' => $request->payment_method,
            'subtotal' => $subtotal,
            'service_fee' => $service,
            'delivery_fee' => $delivery,
            'tax' => $tax,
            'total' => $grandTotal,
            'status' => 'pending',
            'courier' => 'ZestShop Courier',
            'eta' => now()->addDays(2)->format('d/m/Y'),
        ]);

        foreach ($cart as $key => $item) {
            $productId = $item['id'] ?? (is_numeric($key) ? $key : null);

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $productId,
                'product_title' => $item['title'],
                'product_thumbnail' => $item['thumbnail'] ?? null,
                'price' => $item['price'] ?? 2.50,
                'qty' => $item['qty'],
            ]);

            // Update product stock and sales
            if ($productId) {
                $product = Product::find($productId);
                if ($product) {
                    $product->decrement('stock', $item['qty']);
                    $product->increment('sales', $item['qty']);
                }
            }
        }

        // Clear cart
        session()->forget('cart');
        
        // Save order number in session for tracking
        session()->put('last_order_number', $orderNumber);

        return redirect()->route('delivery.track', ['order_number' => $orderNumber])
            ->with('success', 'Order placed successfully! Your order number is ' . $orderNumber);
    }
}
