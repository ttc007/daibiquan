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
            $months = collect();
            for ($i = 11; $i >= 0; $i--) {
                $months->push(now()->subMonths($i)->format('Y-m'));
            }
            // Lượt truy cập theo tháng
            $rawVisits = DB::table('connects')
                ->select(DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"), DB::raw('COUNT(*) as count'))
                ->where('created_at', '>=', now()->subMonths(12))
                ->groupBy('month')
                ->pluck('count', 'month');

            $visits = $months->map(fn($m) => [
                'date' => $m,
                'count' => $rawVisits[$m] ?? 0
            ]);

            // Đơn hàng theo tháng
            $rawOrders = DB::table('orders')
                ->select(
                    DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"),
                    DB::raw("SUM(CASE WHEN status = 'Hủy' THEN total_price ELSE 0 END) as total_huy"),
                    DB::raw("SUM(CASE WHEN status = 'Hoàn thành' THEN total_price ELSE 0 END) as total_hoanthanh"),
                    DB::raw("SUM(CASE WHEN status NOT IN ('Hủy', 'Hoàn thành') THEN total_price ELSE 0 END) as total_khac")
                )
                ->where('created_at', '>=', now()->subMonths(12))
                ->groupBy('month')
                ->get()
                ->keyBy('month');

            $orders = $months->map(function ($m) use ($rawOrders) {
                $data = $rawOrders[$m] ?? (object)[
                    'total_huy' => 0,
                    'total_hoanthanh' => 0,
                    'total_khac' => 0
                ];
                return [
                    'date' => $m,
                    'total_huy' => $data->total_huy,
                    'total_hoanthanh' => $data->total_hoanthanh,
                    'total_khac' => $data->total_khac
                ];
            });

        } elseif ($type === 'week') {
            // 10 tuần gần nhất (mỗi tuần = thứ hai đầu tuần)
            $weeks = collect();
            for ($i = 11; $i >= 0; $i--) {
                $monday = now()->startOfWeek()->subWeeks($i)->format('Y-m-d');
                $weeks->push($monday);
            }

            // Truy cập theo tuần
            $rawVisits = DB::table('connects')
                ->select(DB::raw("DATE_FORMAT(DATE_SUB(created_at, INTERVAL WEEKDAY(created_at) DAY), '%Y-%m-%d') as week_start"), DB::raw('COUNT(*) as count'))
                ->where('created_at', '>=', now()->startOfWeek()->subWeeks(12))
                ->groupBy('week_start')
                ->pluck('count', 'week_start');

            $visits = $weeks->map(fn($w) => [
                'date' => $w,
                'count' => $rawVisits[$w] ?? 0
            ]);

            // Đơn hàng theo tuần
            $rawOrders = DB::table('orders')
                ->select(
                    DB::raw("DATE_FORMAT(DATE_SUB(created_at, INTERVAL WEEKDAY(created_at) DAY), '%Y-%m-%d') as week_start"),
                    DB::raw("SUM(CASE WHEN status = 'Hủy' THEN total_price ELSE 0 END) as total_huy"),
                    DB::raw("SUM(CASE WHEN status = 'Hoàn thành' THEN total_price ELSE 0 END) as total_hoanthanh"),
                    DB::raw("SUM(CASE WHEN status NOT IN ('Hủy', 'Hoàn thành') THEN total_price ELSE 0 END) as total_khac")
                )
                ->where('created_at', '>=', now()->startOfWeek()->subWeeks(12))
                ->groupBy('week_start')
                ->get()
                ->keyBy('week_start');

            $orders = $weeks->map(function ($w) use ($rawOrders) {
                $data = $rawOrders[$w] ?? (object)[
                    'total_huy' => 0,
                    'total_hoanthanh' => 0,
                    'total_khac' => 0
                ];
                return [
                    'date' => $w,
                    'total_huy' => $data->total_huy,
                    'total_hoanthanh' => $data->total_hoanthanh,
                    'total_khac' => $data->total_khac
                ];
            });

        } else {
            // Mốc thời gian: 30 ngày gần nhất
            $dates = collect();
            for ($i = 11; $i >= 0; $i--) {
                $dates->push(now()->subDays($i)->format('Y-m-d'));
            }

            // Dữ liệu truy cập
            $rawVisits = DB::table('connects')
                ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
                ->where('created_at', '>=', now()->subDays(12))
                ->groupBy('date')
                ->pluck('count', 'date'); // key = date, value = count

            $visits = $dates->map(fn($date) => [
                'date' => $date,
                'count' => $rawVisits[$date] ?? 0
            ]);

            // Dữ liệu đơn hàng
            $rawOrders = DB::table('orders')
                ->select(
                    DB::raw('DATE(created_at) as date'),
                    DB::raw("SUM(CASE WHEN status = 'Hủy' THEN total_price ELSE 0 END) as total_huy"),
                    DB::raw("SUM(CASE WHEN status = 'Hoàn thành' THEN total_price ELSE 0 END) as total_hoanthanh"),
                    DB::raw("SUM(CASE WHEN status NOT IN ('Hủy', 'Hoàn thành') THEN total_price ELSE 0 END) as total_khac")
                )
                ->where('created_at', '>=', now()->subDays(12))
                ->groupBy('date')
                ->get()
                ->keyBy('date');

            $orders = $dates->map(function ($date) use ($rawOrders) {
                $data = $rawOrders[$date] ?? (object)[
                    'total_huy' => 0,
                    'total_hoanthanh' => 0,
                    'total_khac' => 0
                ];
                return [
                    'date' => $date,
                    'total_huy' => $data->total_huy,
                    'total_hoanthanh' => $data->total_hoanthanh,
                    'total_khac' => $data->total_khac
                ];
            });
        }

        return response()->json([
            'visits' => $visits,
            'orders' => $orders,
        ]);
    }


}
