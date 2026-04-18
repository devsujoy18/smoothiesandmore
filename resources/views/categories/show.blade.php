<x-layouts::app :title="$category->name">
    <div class="max-w-4xl mx-auto">
        @if (session()->has('message'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('message') }}
            </div>
        @endif

        <div class="bg-white dark:bg-zinc-800 rounded-lg shadow overflow-hidden">
            <div class="md:flex">
                <!-- Category Image -->
                <div class="md:flex-shrink-0 md:w-2/5">
                    @if ($category->image)
                        <img src="{{ asset($category->image) }}" alt="{{ $category->name }}" class="h-80 w-full object-cover">
                    @else
                        <div class="h-80 w-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                            <span class="text-gray-500">No image</span>
                        </div>
                    @endif
                </div>

                <!-- Category Details -->
                <div class="p-8 md:w-3/5">
                    <div class="space-y-6">
                        <div>
                            <h1 class="text-4xl font-bold text-gray-900 dark:text-white">{{ $category->name }}</h1>
                            <p class="text-gray-600 dark:text-gray-400">{{ $category->slug }}</p>
                        </div>

                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Products in this Category</h3>
                            <p class="text-3xl font-bold text-blue-600">{{ $category->products->count() }}</p>
                        </div>

                        @if ($category->products->count())
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Product List</h3>
                                <div class="space-y-2">
                                    @foreach ($category->products as $product)
                                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-zinc-700 rounded">
                                            <div>
                                                <div class="font-medium text-gray-900 dark:text-white">{{ $product->name }}</div>
                                                <div class="text-sm text-gray-500">₹{{ number_format($product->price, 2) }}</div>
                                            </div>
                                            <a href="{{ route('products.show', $product) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400">View</a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="pt-6 space-y-3 border-t border-gray-200 dark:border-gray-700">
                            <div class="flex gap-2">
                                <a href="{{ route('categories.edit', $category) }}" class="flex-1 bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-lg text-center font-medium">
                                    Edit Category
                                </a>
                            </div>
                            <div class="flex gap-2">
                                <form action="{{ route('categories.destroy', $category) }}" method="POST" class="flex-1">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Are you sure you want to delete this category?')" class="w-full bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg font-medium">
                                        Delete Category
                                    </button>
                                </form>
                            </div>
                            <a href="{{ route('categories.index') }}" class="block bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg text-center">
                                Back to Categories
                            </a>
                        </div>

                        <!-- Meta Information -->
                        <div class="pt-6 border-t border-gray-200 dark:border-gray-700 space-y-2 text-sm text-gray-600 dark:text-gray-400">
                            <p><span class="font-medium">Created:</span> {{ $category->created_at->format('F j, Y') }}</p>
                            <p><span class="font-medium">Updated:</span> {{ $category->updated_at->diffForHumans() }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts::app>
