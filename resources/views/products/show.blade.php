<x-layouts::app :title="$product->name">
<div class="max-w-4xl mx-auto">
    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('message') }}
        </div>
    @endif

    <div class="bg-white dark:bg-zinc-800 rounded-lg shadow overflow-hidden">
        <div class="md:flex">
            <!-- Product Image -->
            <div class="md:flex-shrink-0 md:w-2/5">
                @if ($product->image)
                    <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="h-80 w-full object-cover">
                @else
                    <div class="h-80 w-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                        <span class="text-gray-500">No image</span>
                    </div>
                @endif
            </div>

            <!-- Product Details -->
            <div class="p-8 md:w-3/5">
                <div class="space-y-6">
                    <div>
                        <h1 class="text-4xl font-bold text-gray-900 dark:text-white">{{ $product->name }}</h1>
                        <p class="text-gray-600 dark:text-gray-400">{{ $product->category->name }}</p>
                    </div>

                    <div>
                        <p class="text-3xl font-bold text-blue-600">
                            ₹{{ number_format($product->price, 2) }}
                            @if ($product->mrp)
                                <span class="line-through text-gray-500 dark:text-gray-400 text-xl ml-2">${{ number_format($product->mrp, 2) }}</span>
                            @endif
                        </p>
                        @if ($product->mrp && $product->price < $product->mrp)
                            <p class="text-green-600 dark:text-green-400 text-sm mt-1">
                                Save {{ number_format((($product->mrp - $product->price) / $product->mrp) * 100, 0) }}%
                            </p>
                        @endif
                    </div>

                    <div>
                        <span class="px-4 py-2 rounded-full text-sm font-medium {{ $product->is_available ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' }}">
                            {{ $product->is_available ? '✓ Available' : '✗ Unavailable' }}
                        </span>
                    </div>

                    @if ($product->description)
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Description</h3>
                            <p class="text-gray-600 dark:text-gray-400 leading-relaxed">{{ $product->description }}</p>
                        </div>
                    @endif

                    @if ($product->addons && $product->addons->count())
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Available Add-ons</h3>
                            <div class="space-y-2">
                                @foreach($product->addons as $addon)
                                    <div class="flex items-center justify-between px-3 py-2 bg-gray-50 dark:bg-zinc-700 rounded">
                                        <div class="flex items-center gap-3">
                                            @if($addon->image)
                                                <img src="{{ asset($addon->image) }}" alt="{{ $addon->name }}" class="w-8 h-8 rounded object-cover">
                                            @endif
                                            <div>
                                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $addon->name }}</div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400">Min: {{ $addon->pivot->min_selection }} • Max: {{ $addon->pivot->max_selection }} @if($addon->pivot->is_required) • Required @endif</div>
                                            </div>
                                        </div>
                                        <div class="text-sm font-semibold text-gray-900 dark:text-white">₹{{ number_format($addon->price, 2) }}</div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="pt-6 space-y-3 border-t border-gray-200 dark:border-gray-700">
                        <div class="flex gap-2">
                            <a href="{{ route('products.edit', $product) }}" class="flex-1 bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-lg text-center font-medium">
                                Edit Product
                            </a>
                            <a href="{{ route('products.addons', $product) }}" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg text-center font-medium">
                                Manage Add-ons
                            </a>
                        </div>
                        <div class="flex gap-2">
                            <form action="{{ route('products.destroy', $product) }}" method="POST" class="flex-1">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Are you sure you want to delete this product?')" class="w-full bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg font-medium">
                                    Delete Product
                                </button>
                            </form>
                        </div>
                        <a href="{{ route('products.index') }}" class="block bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg text-center">
                            Back to Products
                        </a>
                    </div>

                    <!-- Meta Information -->
                    <div class="pt-6 border-t border-gray-200 dark:border-gray-700 space-y-2 text-sm text-gray-600 dark:text-gray-400">
                        <p><span class="font-medium">Created:</span> {{ $product->created_at->format('F j, Y') }}</p>
                        <p><span class="font-medium">Updated:</span> {{ $product->updated_at->diffForHumans() }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</x-layouts::app>
