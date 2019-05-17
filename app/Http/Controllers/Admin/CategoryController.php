<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        $categories = Category::paginate(15);

        return view('admin.categories.index', compact('categories'));
    }

    public function create(): View
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'name' => 'required|unique:categories'
        ]);

        $category = new Category();
        $category->name = $request->get('name', null);
        $category->save();

        $categories = Category::paginate(15);
        return view('admin.categories.index', compact('categories'));
    }

    public function edit($categoryId): View
    {
        $category = Category::findOrFail($categoryId);
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, $categoryId)
    {
        $this->validate($request,[
            'name' => 'required|unique:categories'
        ]);
        $category = Category::findOrFail($categoryId);

        $category->name = $request->get('name');
        $category->save();

        $categories = Category::paginate(15);
        return view('admin.categories.index', compact('categories'));
    }

    public function destroy($categoryId)
    {
        $category = Category::findOrFail($categoryId);
        $category->delete();

        $categories = Category::paginate(15);
        return view('admin.categories.index', compact('categories'));
    }
}