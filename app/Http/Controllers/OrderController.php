<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderPlacedMail;

class OrderController extends Controller
{
    // Hiển thị form checkout
    public function checkout()
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng đang trống!');
        }
        return view('order.checkout', compact('cart'));
    }

    // Xử lý đặt hàng
    public function placeOrder(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'customer_id' => 'nullable|string|max:255',
        ]);

        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng đang trống!');
        }

        // Ở đây bạn có thể lưu đơn hàng vào CSDL hoặc gửi email, xử lý thanh toán ...
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        // Lưu đơn hàng
        $order = Order::create([
            'customer_id' => $request->customer_id ?? null,
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
            'total_price' => $total,
            'status' => 'Mới'
        ]);

        // Lưu các sản phẩm trong đơn hàng
        foreach ($cart as $productId => $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $productId,
                'product_name' => $item['name'],
                'price' => $item['price'],
                'quantity' => $item['quantity'],
            ]);
        }

        // Ví dụ xóa giỏ hàng sau khi đặt
        session()->forget('cart');

        $orderItems = OrderItem::where('order_id', $order->id)->get();
        // Gửi mail cho admin
        Mail::to('truongthanhcong1909@gmail.com')->send(new OrderPlacedMail($order, $orderItems));
        
        return redirect()->route('orders.history')->with('success', 'Đặt hàng thành công! Chúng tôi sẽ liên hệ bạn sớm.');
    }

    public function history(Request $request)
    {
        return view('order.history');
    }
}
