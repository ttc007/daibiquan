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
        $type = $request->query('type', 'day');

        if ($type === 'month') {
            // Thống kê theo tháng: 6 tháng gần nhất
            $visits = DB::table('connects')
                ->select(
                    DB::raw("DATE_FORMAT(created_at, '%m/%Y') as date"),
                    DB::raw("COUNT(*) as count")
                )
                ->where('created_at', '>=', now()->subMonths(10))
                ->groupBy('date')
                ->orderBy('date')
                ->get();

            $orders = DB::table('orders')
                ->select(
                    DB::raw("DATE_FORMAT(created_at, '%m/%Y') as date"),
                    DB::raw("SUM(CASE WHEN status = 'Hoàn thành' THEN total_price ELSE 0 END) as total_hoanthanh"),
                    DB::raw("SUM(CASE WHEN status = 'Hủy' THEN total_price ELSE 0 END) as total_huy"),
                    DB::raw("SUM(CASE WHEN status NOT IN ('Hoàn thành', 'Hủy') THEN total_price ELSE 0 END) as total_khac")
                )
                ->where('created_at', '>=', now()->subMonths(10))
                ->groupBy('date')
                ->orderBy('date')
                ->get();

        } elseif ($type === 'week') {
            // Thống kê theo tuần: 8 tuần gần nhất
            $visits = DB::table('connects')
                ->select(
                    DB::raw("YEARWEEK(created_at, 1) as week"),
                    DB::raw("COUNT(*) as count")
                )
                ->where('created_at', '>=', now()->subWeeks(10))
                ->groupBy('week')
                ->orderBy('week')
                ->get()
                ->map(function ($item) {
                    $year = substr($item->week, 0, 4);
                    $week = substr($item->week, 4, 2);
                    $item->date = "{$year}-W{$week}";
                    return $item;
                });

            $orders = DB::table('orders')
                ->select(
                    DB::raw("YEARWEEK(created_at, 1) as week"),
                    DB::raw("SUM(CASE WHEN status = 'Hoàn thành' THEN total_price ELSE 0 END) as total_hoanthanh"),
                    DB::raw("SUM(CASE WHEN status = 'Hủy' THEN total_price ELSE 0 END) as total_huy"),
                    DB::raw("SUM(CASE WHEN status NOT IN ('Hoàn thành', 'Hủy') THEN total_price ELSE 0 END) as total_khac")
                )
                ->where('created_at', '>=', now()->subWeeks(10))
                ->groupBy('week')
                ->orderBy('week')
                ->get()
                ->map(function ($item) {
                    $year = substr($item->week, 0, 4);
                    $week = substr($item->week, 4, 2);
                    $item->date = "{$year}-W{$week}";
                    return $item;
                });

        } else {
            // Thống kê theo ngày: 30 ngày gần nhất
            $visits = DB::table('connects')
                ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
                ->where('created_at', '>=', now()->subDays(10))
                ->groupBy('date')
                ->orderBy('date')
                ->get();

            $orders = DB::table('orders')
                ->select(
                    DB::raw('DATE(created_at) as date'),
                    DB::raw("SUM(CASE WHEN status = 'Hoàn thành' THEN total_price ELSE 0 END) as total_hoanthanh"),
                    DB::raw("SUM(CASE WHEN status = 'Hủy' THEN total_price ELSE 0 END) as total_huy"),
                    DB::raw("SUM(CASE WHEN status NOT IN ('Hoàn thành', 'Hủy') THEN total_price ELSE 0 END) as total_khac")
                )
                ->where('created_at', '>=', now()->subDays(10))
                ->groupBy('date')
                ->orderBy('date')
                ->get();
        }

        return response()->json([
            'visits' => $visits,
            'orders' => $orders,
        ]);
    }


}
