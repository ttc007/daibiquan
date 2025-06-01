<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class ProductController extends Controller
{
    public function index() {
        $products = Product::with('category')->latest()->paginate(10);
        $categories = Category::all();
        return view('menu', compact('products', 'categories'));
    }

    public function byCategory($id)
    {
        $category = Category::findOrFail($id);
        $categories = Category::all();
        $products = Product::where('category_id', $id)->latest()->paginate(10);

        return view('menu', compact('products', 'categories', 'category'));
    }
}
