<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->latest()->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
             'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('products', 'name')
            ],
            'price' => 'required|integer|min:0',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'category_id' => 'required|exists:categories,id',
        ], [
            'name.unique' => 'Tên sản phẩm đã tồn tại.',
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $extension = $image->getClientOriginalExtension(); // lấy đuôi thực của ảnh
            $imageName = time() . '_' . preg_replace('/\s+/', '_', $request->name) . '.' . $extension;

            $image->move(public_path('storage/products'), $imageName);
            $validated['image_path'] = 'storage/products/' . $imageName;
        } else {
            $validated['image_path'] = null;  // không có ảnh thì null
        }

        Product::create([
            'name'        => $request->name,
            'price'       => $request->price,
            'description' => $request->description,
            'image'       => $validated['image_path'],
            'category_id' => $request->category_id,  // thêm trường category_id
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Thêm sản phẩm thành công');
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();

        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
             'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('products', 'name')->ignore($product->id)
            ],
            'price' => 'required|integer|min:0',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'category_id' => 'required|exists:categories,id',
        ], [
            'name.unique' => 'Tên sản phẩm đã tồn tại.',
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $extension = $image->getClientOriginalExtension(); // lấy đuôi thực của ảnh
            $imageName = time() . '_' . preg_replace('/\s+/', '_', $request->name) . '.' . $extension;

            $image->move(public_path('storage/products'), $imageName);
            $validated['image_path'] = 'storage/products/' . $imageName;
        } else {
            $validated['image_path'] = $product->image;  // không có ảnh thì null
        }

        $product->update([
            'name'        => $request->name,
            'price'       => $request->price,
            'description' => $request->description,
            'image'       => $validated['image_path'],
            'category_id' => $request->category_id,  // thêm trường category_id
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Cập nhật sản phẩm thành công');
    }

    public function destroy($id)
    {
        Product::destroy($id);
        return redirect()->route('admin.products.index')->with('success', 'Xóa sản phẩm thành công');
    }
}
