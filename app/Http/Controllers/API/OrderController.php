<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $customer = auth('customers')->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'phone' => 'required|string|max:20',
        ]);

        DB::beginTransaction();

        try {
            // Get cart items for customer
            $cartItems = Cart::with('product')
                ->where('customer_id', $customer->id)
                ->get();

            if ($cartItems->isEmpty()) {
                return response()->json([
                    'message' => 'Your cart is empty.',
                ], 400);
            }

            // Calculate total
            $totalAmount = $cartItems->sum(function ($item) {
                return $item->product->price * $item->quantity;
            });

            // Create Order
            $order = Order::create([
                'customer_id' => $customer->id,
                'name'        => $request->name,
                'address'     => $request->address,
                'phone'       => $request->phone,
                'total'       => $totalAmount,
                'status'      => 'pending',
            ]);

            // Add Order Items
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $item->product_id,
                    'quantity'   => $item->quantity,
                    'price'      => $item->product->price,
                ]);
            }

            // Clear Cart
            Cart::where('customer_id', $customer->id)->delete();

            DB::commit();

            return response()->json([
                'message' => 'Order placed successfully',
                'order'   => $order,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Order placement failed',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}
