<?php

// app/Http/Controllers/API/CartController.php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cart = Cart::with('product')->where('customer_id', Auth::id())->get();
        return response()->json(['data' => $cart]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);
    
        $customer = auth('customers')->user(); // Use the 'customers' guard
    
        if (!$customer) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
    
        $cart = new Cart();
        $cart->customer_id = $customer->id;
        $cart->product_id = $request->product_id;
        $cart->quantity = $request->quantity;
        $cart->save();
    
        return response()->json([
            'message' => 'Product added to cart successfully',
            'cart' => $cart,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cart = Cart::where('id', $id)->where('customer_id', Auth::id())->firstOrFail();
        $cart->update(['quantity' => $request->quantity]);

        return response()->json(['message' => 'Cart updated', 'data' => $cart]);
    }

    public function destroy($id)
    {
        $cart = Cart::where('id', $id)->where('customer_id', Auth::id())->firstOrFail();
        $cart->delete();

        return response()->json(['message' => 'Item removed from cart']);
    }
}

