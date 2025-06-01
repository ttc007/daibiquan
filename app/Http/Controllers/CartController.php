<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        return view('cart.index', compact('cart'));
    }

    public function add(Request $request, $id)
    {   
        $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);


        $product = Product::findOrFail($id);

        $quantity = max(1, (int) $request->input('quantity', 1)); // Lấy quantity từ form, tối thiểu là 1

        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            $cart[$id]['quantity'] += $quantity; // Cộng thêm số lượng mới
        } else {
            $cart[$id] = [
                'name' => $product->name,
                'quantity' => $quantity,
                'price' => $product->price,
                'image' => $product->image,
            ];
        }

        session()->put('cart', $cart);

        return redirect()->back()->with('success', 'Đã thêm vào giỏ hàng!');
    }

    public function update(Request $request, $id)
    {
        $quantity = $request->input('quantity');
        $cart = session()->get('cart', []);

        if(isset($cart[$id]) && $quantity > 0) {
            $cart[$id]['quantity'] = $quantity;
            session()->put('cart', $cart);
            return redirect()->back()->with('success', 'Cập nhật giỏ hàng thành công!');
        }

        return redirect()->back()->with('error', 'Sản phẩm không tồn tại hoặc số lượng không hợp lệ.');
    }

    public function increase($id)
    {
        $cart = session()->get('cart', []);
        if(isset($cart[$id])) {
            $cart[$id]['quantity']++;
            session()->put('cart', $cart);
        }
        return redirect()->back();
    }

    public function decrease($id)
    {
        $cart = session()->get('cart', []);
        if(isset($cart[$id])) {
            $cart[$id]['quantity']--;
            if ($cart[$id]['quantity'] <= 0) {
                unset($cart[$id]);
            }
            session()->put('cart', $cart);
        }
        return redirect()->back();
    }

    public function remove(Request $request, $id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        return redirect()->back()->with('success', 'Đã xoá sản phẩm khỏi giỏ hàng!');
    }
}
