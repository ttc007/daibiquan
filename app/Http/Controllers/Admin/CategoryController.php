<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories', 'name')
            ]
        ], [
            'name.unique' => 'Tên danh mục đã tồn tại.',
        ]);

        Category::create(['name' => $request->name]);

        return redirect()->route('admin.categories.index')->with('success', 'Danh mục đã được thêm.');
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories', 'name')->ignore($category->id)
            ]
        ], [
            'name.unique' => 'Tên danh mục đã tồn tại.',
        ]);

        $category->update(['name' => $request->name]);

        return redirect()->route('admin.categories.index')->with('success', 'Cập nhật thành công.');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Đã xóa danh mục.');
    }
}
