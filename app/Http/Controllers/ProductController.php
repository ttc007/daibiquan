<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class ProductController extends Controller
{
    public function index() {
        $quanChayCategoryId = 1; // ID quán chay Đại Bi
        $cuaHangCategoryId = 2;  // ID cửa hàng Đại Bi

        $quanChayProducts = Product::where('category_id', $quanChayCategoryId)->latest()->paginate(10);
        $cuaHangProducts = Product::where('category_id', $cuaHangCategoryId)->latest()->paginate(10);

        return view('menu', compact('quanChayProducts', 'cuaHangProducts'));
    }

    public function byCategory($id)
    {
        $categories = Category::all();
        $products = Product::where('category_id', $id)->latest()->paginate(10);

        return view('menu', compact('products', 'categories', 'category'));
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);

        return view('product', compact('product'));
    }
}
