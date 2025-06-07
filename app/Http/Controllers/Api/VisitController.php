<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Connect;
use Illuminate\Support\Facades\DB;


class VisitController extends Controller
{
    public function track(Request $request)
    {
        $customerId = $request->header('X-Customer-ID');

        // Nếu không có customer_id thì bỏ qua
        if (!$customerId) {
            return response()->json(['status' => 'no_customer_id'], 400);
        }

        $lastVisit = Connect::where('customer_id', $customerId)
            ->orderByDesc('created_at')
            ->first();

        // Tạo mới nếu chưa từng truy cập hoặc đã hơn 4 giờ
        if (!$lastVisit || $lastVisit->created_at->lt(now()->subHours(4))) {
            Connect::create([
                'customer_id' => $customerId,
                'ip' => $request->ip(),
            ]);
        }

        return response()->json(['status' => 'ok']);
    }

    public function getVisitsByDate(Request $request)
    {
        // Lấy lượt truy cập theo ngày trong 30 ngày gần nhất
        $visits = DB::table('connects')
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Lấy tổng doanh thu theo ngày, chia theo trạng thái đơn hàng
        $orders = DB::table('orders')
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw("SUM(CASE WHEN status = 'Mới' THEN total_price ELSE 0 END) as total_moi"),
                DB::raw("SUM(CASE WHEN status = 'Hoàn thành' THEN total_price ELSE 0 END) as total_hoanthanh"),
                DB::raw("SUM(CASE WHEN status NOT IN ('Mới', 'Hoàn thành') THEN total_price ELSE 0 END) as total_khac")
            )
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return response()->json([
            'visits' => $visits,
            'orders' => $orders,
        ]);
    }

}
