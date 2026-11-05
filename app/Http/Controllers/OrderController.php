<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['diningTable', 'items'])
            ->latest()
            ->paginate(15);

        $waitingCount = Order::where('status', 'menunggu')->count();
        $processingCount = Order::where('status', 'diproses')->count();
        $completedCount = Order::where('status', 'selesai')->count();

        return view('orders.index', compact(
            'orders',
            'waitingCount',
            'processingCount',
            'completedCount'
        ));
    }

    public function show(Order $order)
    {
        $order->load(['diningTable', 'items.product', 'sale']);

        return view('orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:diproses'],
        ]);

        if ($order->sale_id !== null || $order->paid_at !== null) {
            return back()->with(
                'error',
                'Pesanan ini sudah dibayar dan tidak dapat diubah melalui menu ini.'
            );
        }

        if ($order->status !== 'menunggu') {
            return back()->with(
                'error',
                'Hanya pesanan yang masih menunggu yang dapat diproses.'
            );
        }

        $order->update([
            'status' => $validated['status'],
        ]);

        return redirect()
            ->route('orders.show', $order)
            ->with('success', 'Pesanan berhasil diubah menjadi sedang diproses.');
    }
}
