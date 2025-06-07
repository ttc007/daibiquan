<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderPlacedMail;
use App\Models\Product;

class OrderController extends Controller
{
    public function checkout($type)
    {
        $cart = session()->get('cart', []);

        $filteredCart = [];
        foreach ($cart as $id => $item) {
            $product = Product::find($id);
            if ($product && $type === 'com_chay' && $product->category_id == 1) {
                $filteredCart[$id] = $item;
            }
            if ($product && $type === 'cua_hang' && $product->category_id == 2) {
                $filteredCart[$id] = $item;
            }
        }

        return view('order.checkout', compact('filteredCart', 'type'));
    }

    // Xử lý đặt hàng
    public function placeOrder(Request $request, $type)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'customer_id' => 'nullable|string|max:255',
        ]);

        $fullCart = session()->get('cart', []);

        $cart = [];
        foreach ($fullCart as $id => $item) {
            $product = Product::find($id);
            if ($product && $type === 'com_chay' && $product->category_id == 1) {
                $cart[$id] = $item;
            }
            if ($product && $type === 'cua_hang' && $product->category_id == 2) {
                $cart[$id] = $item;
            }
        }

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

        // Xóa phần giỏ hàng thuộc loại đó
        foreach ($cart as $productId => $_) {
            unset($fullCart[$productId]);
        }
        session()->put('cart', $fullCart);

        $orderItems = OrderItem::where('order_id', $order->id)->get();
        // Gửi mail cho admin
        $adminEmail = env('ADMIN_EMAIL', 'truongthanhcong1909@gmail.com'); // fallback nếu chưa có biến env
        Mail::to($adminEmail)->send(new OrderPlacedMail($order, $orderItems));
        
        return redirect()->route('orders.history')->with('success', 'Đặt hàng thành công! Chúng tôi sẽ liên hệ bạn sớm.');
    }

    public function history(Request $request)
    {
        return view('order.history');
    }

    public function reorder($id)
    {
        $order = Order::findOrFail($id);

        $items = OrderItem::where('order_id', $order->id)->get();

        $cart = [];

        foreach ($items as $item) {
            $product = $item->product;

            // Kiểm tra nếu sản phẩm vẫn còn
            if ($product) {
                $cart[$product->id] = [
                    'name' => $product->name,
                    'quantity' => $item->quantity,
                    'price' => $product->price,
                    'image' => $product->image,
                ];
            }
        }

        session()->put('cart', $cart);

        return redirect()->route('checkout.placeOrder')->with('success', 'Đã sao chép đơn hàng cũ vào giỏ để mua lại!');
    }

}
