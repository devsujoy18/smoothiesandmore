<x-layouts::app :title="'Edit ' . $product->name">
<div class="max-w-2xl mx-auto">
    <div class="bg-white dark:bg-zinc-800 rounded-lg shadow p-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Edit Product</h1>

        <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="block text-sm font-medium text-gray-900 dark:text-gray-100 mb-1">
                    Product Name <span class="text-red-500">*</span>
                </label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name', $product->name) }}"
                    class="w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-zinc-700 px-3 py-2 text-gray-900 dark:text-white"
                    placeholder="Enter product name"
                    required
                />
                @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="sort_no" class="block text-sm font-medium text-gray-900 dark:text-gray-100 mb-1">
                    Sort No
                </label>
                <input
                    type="number"
                    id="sort_no"
                    name="sort_no"
                    value="{{ old('sort_no', $product->sort_no) }}"
                    class="w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-zinc-700 px-3 py-2 text-gray-900 dark:text-white"
                    placeholder="0"
                />
                @error('sort_no') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="category_id" class="block text-sm font-medium text-gray-900 dark:text-gray-100 mb-1">
                    Category <span class="text-red-500">*</span>
                </label>
                <select
                    id="category_id"
                    name="category_id"
                    class="w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-zinc-700 px-3 py-2 text-gray-900 dark:text-white"
                    required
                >
                    <option value="">Select a category</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="price" class="block text-sm font-medium text-gray-900 dark:text-gray-100 mb-1">
                    Price <span class="text-red-500">*</span>
                </label>
                <input
                    type="number"
                    id="price"
                    name="price"
                    value="{{ old('price', $product->price) }}"
                    step="0.01"
                    min="0"
                    class="w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-zinc-700 px-3 py-2 text-gray-900 dark:text-white"
                    placeholder="₹ 0.00"
                    required
                />
                @error('price') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="mrp" class="block text-sm font-medium text-gray-900 dark:text-gray-100 mb-1">
                    MRP (Strike-through Price)
                </label>
                <input
                    type="number"
                    id="mrp"
                    name="mrp"
                    value="{{ old('mrp', $product->mrp) }}"
                    step="0.01"
                    min="0"
                    class="w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-zinc-700 px-3 py-2 text-gray-900 dark:text-white"
                    placeholder="₹ 0.00"
                />
                @error('mrp') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-gray-900 dark:text-gray-100 mb-1">
                    Description
                </label>
                <textarea
                    id="description"
                    name="description"
                    rows="4"
                    class="w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-zinc-700 px-3 py-2 text-gray-900 dark:text-white"
                    placeholder="Enter product description"
                >{{ old('description', $product->description) }}</textarea>
                @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="image" class="block text-sm font-medium text-gray-900 dark:text-gray-100 mb-1">
                    Product Image
                </label>
                <input
                    type="file"
                    id="image"
                    name="image"
                    accept="image/*"
                    class="w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-zinc-700 px-3 py-2 text-gray-900 dark:text-white"
                />
                @error('image') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

                @if ($product->image)
                    <div class="mt-4">
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Current Image:</p>
                        <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="h-32 w-32 object-cover rounded">
                    </div>
                @endif
            </div>

            <div>
                <label for="is_available" class="flex items-center gap-2">
                    <input
                        type="checkbox"
                        id="is_available"
                        name="is_available"
                        value="1"
                        {{ old('is_available', $product->is_available) ? 'checked' : '' }}
                        class="rounded"
                    />
                    <span class="text-sm font-medium text-gray-900 dark:text-gray-100">Available for Order</span>
                </label>
            </div>

            <div class="flex gap-3 pt-4">
                <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-2 rounded-lg">
                    Update Product
                </button>
                <a href="{{ route('products.show', $product) }}" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
</x-layouts::app>
