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
                    ->where('customer_id', $customerId)
                    ->orderByDesc('created_at')
                    ->get();

        return response()->json($orders);
    }
}
