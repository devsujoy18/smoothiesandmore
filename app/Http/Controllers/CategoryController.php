<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the categories.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');

        $categories = Category::query()
            ->when($search, function ($q, $s) {
                $q->where('name', 'like', "%{$s}%");
            })
            ->withCount('products')
            ->orderBy('sort_no')
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('categories.index', compact('categories', 'search'));
    }

    /**
     * Show the form for creating a new category.
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * Store a newly created category in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:2048',
            'sort_no' => 'nullable|integer',
        ]);

        $data = $validated;

        if ($request->hasFile('image')) {
            $uploadPath = public_path('images/categories');
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            $fileName = time() . '_' . uniqid() . '.' . $request->file('image')->getClientOriginalExtension();
            copy($request->file('image')->getRealPath(), $uploadPath . '/' . $fileName);
            $data['image'] = 'images/categories/' . $fileName;
        }

        Category::create($data);

        return redirect()->route('categories.index')->with('message', 'Category created successfully.');
    }

    /**
     * Display the specified category.
     */
    public function show(Category $category)
    {
        $category->load('products');
        return view('categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified category.
     */
    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    /**
     * Update the specified category in storage.
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:2048',
            'sort_no' => 'nullable|integer',
        ]);

        $data = $validated;

        if ($request->hasFile('image')) {
            // Delete old image
            if ($category->image && file_exists(public_path($category->image))) {
                @unlink(public_path($category->image));
            }

            $uploadPath = public_path('images/categories');
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            $fileName = time() . '_' . uniqid() . '.' . $request->file('image')->getClientOriginalExtension();
            copy($request->file('image')->getRealPath(), $uploadPath . '/' . $fileName);
            $data['image'] = 'images/categories/' . $fileName;
        }

        $category->update($data);

        return redirect()->route('categories.show', $category)->with('message', 'Category updated successfully.');
    }

    /**
     * Remove the specified category from storage.
     */
    public function destroy(Category $category)
    {
        if ($category->image && file_exists(public_path($category->image))) {
            @unlink(public_path($category->image));
        }

        $category->delete();

        return redirect()->route('categories.index')->with('message', 'Category deleted successfully.');
    }
}
