<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class OrderApiController extends Controller
{
    public function history(Request $request)
    {
        $customerId = $request->header('X-Customer-ID');

        if (!$customerId) {
            return response()->json(['message' => 'Không có customer_id'], 400);
        }

        $orders = Order::with('items')
                ->with(['items.product'])
                ->where('customer_id', $customerId)
                ->orderByDesc('created_at')
                ->paginate(10); // phân trang 10 đơn/lần

        return response()->json($orders);
    }

    public function latestOrder($customerId)
    {
        $order = Order::where('customer_id', $customerId)
                    ->latest()
                    ->first();

        if ($order) {
            return response()->json([
                'name' => $order->name,
                'phone' => $order->phone,
                'address' => $order->address,
            ]);
        }

        return response()->json(null);
    }

    public function cancel(Order $order, Request $request)
    {
        $customerId = $request->header('X-Customer-ID');
        if ($order->customer_id !== $customerId || $order->status !== 'Mới') {
            return response()->json(['error' => 'Không thể hủy đơn hàng'], 403);
        }

        $order->status = 'Hủy';
        $order->save();

        return response()->json(['success' => true]);
    }

    public function received(Order $order, Request $request)
    {
        $customerId = $request->header('X-Customer-ID');
        if ($order->customer_id !== $customerId || !in_array($order->status, ['Đang xử lý', 'Đang giao hàng'])) {
            return response()->json(['error' => 'Không thể xác nhận đơn hàng'], 403);
        }

        $order->status = 'Đã nhận hàng';
        $order->save();

        return response()->json(['success' => true]);
    }
}
