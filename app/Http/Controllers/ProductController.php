<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\AddOn;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the products.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $categoryFilter = $request->query('category');

        $products = Product::query()
            ->with('category')
            ->when($search, function ($q, $s) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('description', 'like', "%{$s}%");
            })
            ->when($categoryFilter, function ($q, $c) {
                $q->where('category_id', $c);
            })
            ->orderBy('sort_no')
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        $categories = Category::orderBy('name')->get();

        return view('products.index', compact('products', 'search', 'categories', 'categoryFilter'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('products.create', compact('categories'));
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'mrp' => 'nullable|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:2048',
            'is_available' => 'boolean',
            'sort_no' => 'nullable|integer',
        ]);

        $data = $validated;
        $data['is_available'] = $request->has('is_available');

        if ($request->hasFile('image')) {
            $uploadPath = public_path('images/products');
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            $fileName = time() . '_' . uniqid() . '.' . $request->file('image')->getClientOriginalExtension();
            copy($request->file('image')->getRealPath(), $uploadPath . '/' . $fileName);
            $data['image'] = 'images/products/' . $fileName;
        }

        Product::create($data);

        return redirect()->route('products.index')->with('message', 'Product created successfully.');
    }

    /**
     * Display the specified product.
     */
    public function show(Product $product)
    {
        $product->load(['category', 'addons']);
        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();
        $product->load('category');
        return view('products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'mrp' => 'nullable|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:2048',
            'is_available' => 'boolean',
            'sort_no' => 'nullable|integer',
        ]);

        $data = $validated;
        $data['is_available'] = $request->has('is_available');

        if ($request->hasFile('image')) {
            // Delete old image
            if ($product->image && file_exists(public_path($product->image))) {
                @unlink(public_path($product->image));
            }

            $uploadPath = public_path('images/products');
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            $fileName = time() . '_' . uniqid() . '.' . $request->file('image')->getClientOriginalExtension();
            copy($request->file('image')->getRealPath(), $uploadPath . '/' . $fileName);
            $data['image'] = 'images/products/' . $fileName;
        }

        $product->update($data);

        return redirect()->route('products.show', $product)->with('message', 'Product updated successfully.');
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(Product $product)
    {
        if ($product->image && file_exists(public_path($product->image))) {
            @unlink(public_path($product->image));
        }

        $product->delete();

        return redirect()->route('products.index')->with('message', 'Product deleted successfully.');
    }

        /**
         * Show the page for managing add-ons for a product.
         */
        public function editAddons(Product $product)
        {
            $productAddons = $product->addons()->get();
            $availableAddons = AddOn::whereNotIn('id', $productAddons->pluck('id'))->get();

            return view('products.addons', compact('product', 'productAddons', 'availableAddons'));
        }

        /**
         * Attach an add-on to a product.
         */
        public function attachAddon(Request $request, Product $product)
        {
            $validated = $request->validate([
                'addon_id' => 'required|exists:addons,id',
                'is_required' => 'nullable|boolean',
                'min_selection' => 'nullable|integer|min:0',
                'max_selection' => 'nullable|integer|min:0',
            ]);

            $product->addons()->attach($validated['addon_id'], [
                'is_required' => $validated['is_required'] ?? false,
                'min_selection' => $validated['min_selection'] ?? 0,
                'max_selection' => $validated['max_selection'] ?? 1,
            ]);

            return response()->json(['success' => true, 'message' => 'Add-on added to product']);
        }

        /**
         * Detach an add-on from a product.
         */
        public function detachAddon(Request $request, AddOn $addon)
        {
            $validated = $request->validate([
                'product_id' => 'required|exists:products,id',
            ]);

            $product = Product::find($validated['product_id']);
            $product->addons()->detach($addon->id);

            return redirect()->route('products.addons', $product)->with('message', 'Add-on removed from product');
        }
}
