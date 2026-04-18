<x-layouts::app :title="'Edit ' . $category->name">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white dark:bg-zinc-800 rounded-lg shadow p-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Edit Category</h1>

            <form action="{{ route('categories.update', $category) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-900 dark:text-gray-100 mb-1">
                        Category Name <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name', $category->name) }}"
                        class="w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-zinc-700 px-3 py-2 text-gray-900 dark:text-white"
                        placeholder="e.g., Smoothies, Juices, Snacks"
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
                        value="{{ old('sort_no', $category->sort_no) }}"
                        class="w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-zinc-700 px-3 py-2 text-gray-900 dark:text-white"
                        placeholder="0"
                    />
                    @error('sort_no') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="image" class="block text-sm font-medium text-gray-900 dark:text-gray-100 mb-1">
                        Category Image
                    </label>
                    <input
                        type="file"
                        id="image"
                        name="image"
                        accept="image/*"
                        class="w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-zinc-700 px-3 py-2 text-gray-900 dark:text-white"
                    />
                    @error('image') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

                    @if ($category->image)
                        <div class="mt-4">
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Current Image:</p>
                            <img src="{{ asset($category->image) }}" alt="{{ $category->name }}" class="h-24 w-24 object-cover rounded">
                        </div>
                    @endif
                </div>

                <div class="flex gap-3 pt-4">
                    <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-2 rounded-lg">
                        Update Category
                    </button>
                    <a href="{{ route('categories.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-layouts::app>
