<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

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
}
