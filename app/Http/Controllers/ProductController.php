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
}
