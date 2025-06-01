<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\Order::query();

        // Đếm số lượng đơn hàng theo từng trạng thái
        $statuses = ['Mới', 'Đang xử lý', 'Đang giao hàng', 'Đã nhận hàng', 'Hoàn thành', 'Hủy'];
        $statusCounts = [];

        foreach ($statuses as $status) {
            $statusCounts[$status] = \App\Models\Order::where('status', $status)->count();
        }

        // Tổng tất cả đơn hàng
        $statusCounts['Tất cả'] = \App\Models\Order::count();

        // Lọc đơn hàng theo status (nếu có)
        if ($request->has('status') && $request->status !== 'Tất cả') {
            $query->where('status', $request->status);
        }

        $orders = $query->latest()->paginate(10);

        return view('admin.orders.index', compact('orders', 'statusCounts'));
    }

    public function show($id)
    {
        $order = Order::with('items.product')->findOrFail($id);
        return view('admin.orders.edit', compact('order'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string|max:255'
        ]);

        $order = Order::findOrFail($id);
        $order->status = $request->input('status');
        $order->save();

        return back()->with('success', 'Cập nhật trạng thái thành công.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
        ]);
        
        $order = Order::findOrFail($id);
        $order->update($request->only(['name', 'phone', 'address', 'status']));

        return redirect()->route('admin.orders.show', $order->id)
                         ->with('success', 'Cập nhật đơn hàng thành công!');
    }

}
