<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1',
        ]);

        $product = Product::find($request->product_id);
        $user = auth()->user();
        $quantity = $request->quantity;
        $total = $product->price * $quantity;

        // check stock
        if ($product->stock < $quantity) {
            return back()->with('error', 'Not enough stock!');
        }

        // check balance
        if ($user->balance < $total) {
            return back()->with('error', 'Insufficient balance!');
        }

        // create order
        Order::create([
            'user_id'     => $user->id,
            'station_id'  => $user->station_id,
            'product_id'  => $product->id,
            'quantity'    => $quantity,
            'total_price' => $total,
            'status'      => 'pending',
        ]);

        // deduct balance and stock
        $user->decrement('balance', $total);
        $product->decrement('stock', $quantity);

        return back()->with('success', "Ordered {$quantity}x {$product->name} for ₱{$total}!");
    }

    public function index()
    {
        $pendingOrders = Order::with(['user', 'station', 'product'])
            ->where('status', 'pending')
            ->get();

        $deliveredOrders = Order::with(['user', 'station', 'product'])
            ->where('status', 'delivered')
            ->latest()
            ->get();

        return view('admin.orders.index', compact('pendingOrders', 'deliveredOrders'));
    }

    public function deliver(Order $order)
    {
        $order->update(['status' => 'delivered']);
        return back()->with('success', 'Order marked as delivered!');
    }

    public function storeMultiple(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $user = auth()->user();
        $errors = [];
        $successCount = 0;

        foreach ($request->items as $item) {
            $product = Product::find($item['product_id']);
            $quantity = $item['quantity'];
            $total = $product->price * $quantity;

            // check stock
            if ($product->stock < $quantity) {
                $errors[] = "{$product->name} - not enough stock!";
                continue;
            }

            // check balance
            if ($user->balance < $total) {
                $errors[] = "{$product->name} - insufficient balance!";
                continue;
            }

            // create order
            Order::create([
                'user_id'     => $user->id,
                'station_id'  => $user->station_id,
                'product_id'  => $product->id,
                'quantity'    => $quantity,
                'total_price' => $total,
                'status'      => 'pending',
            ]);

            // deduct balance and stock
            $user->decrement('balance', $total);
            $product->decrement('stock', $quantity);
            $successCount++;
        }

        if (!empty($errors)) {
            return back()->with('error', implode(', ', $errors));
        }

        return back()->with('success', "{$successCount} item(s) ordered successfully!");
    }
}