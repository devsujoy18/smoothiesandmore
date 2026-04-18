@use('App\Models\Product', 'Product')

<x-layouts::app>
    <div class="min-h-screen bg-white dark:bg-zinc-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Header -->
            <div class="mb-8 flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                        Manage Add-ons for {{ $product->name }}
                    </h1>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">
                        Configure which add-ons are available for this product
                    </p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('products.show', $product) }}" class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-zinc-700 text-gray-800 dark:text-white rounded-lg hover:bg-gray-300 dark:hover:bg-zinc-600 transition">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        Back to Product
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Available Add-ons -->
                <div class="lg:col-span-2">
                    <div class="bg-white dark:bg-zinc-800 rounded-lg shadow-md">
                        <div class="p-6 border-b border-gray-200 dark:border-zinc-700">
                            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
                                Available Add-ons
                            </h2>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                Click an add-on to add it to this product
                            </p>
                        </div>
                        
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gray-50 dark:bg-zinc-900">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Add-on</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Price</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-zinc-700">
                                    @forelse($availableAddons as $addon)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-zinc-700 transition">
                                            <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">
                                                @if($addon->image)
                                                    <div class="flex items-center gap-3">
                                                        <img src="{{ asset($addon->image) }}" alt="{{ $addon->name }}" class="w-8 h-8 rounded object-cover">
                                                        {{ $addon->name }}
                                                    </div>
                                                @else
                                                    {{ $addon->name }}
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-sm font-semibold text-gray-900 dark:text-white">
                                                ₹{{ number_format($addon->price, 2) }}
                                            </td>
                                            <td class="px-6 py-4 text-sm">
                                                <button 
                                                    type="button"
                                                    onclick="openAddOnModal({{ $addon->id }}, '{{ $addon->name }}')"
                                                    class="inline-flex items-center px-3 py-1 bg-green-500 hover:bg-green-600 text-white rounded-lg text-xs font-medium transition">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                    </svg>
                                                    Add
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                                No add-ons available
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Product Add-ons Configuration -->
                <div class="lg:col-span-1">
                    <div class="bg-white dark:bg-zinc-800 rounded-lg shadow-md">
                        <div class="p-6 border-b border-gray-200 dark:border-zinc-700">
                            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
                                Product Add-ons
                            </h2>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                {{ count($productAddons) }} add-on(s) configured
                            </p>
                        </div>
                        
                        <div class="divide-y divide-gray-200 dark:divide-zinc-700">
                            @forelse($productAddons as $productAddon)
                                <div class="p-4 flex items-start justify-between gap-3 hover:bg-gray-50 dark:hover:bg-zinc-700 transition">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                            {{ $productAddon->name }}
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                            ₹{{ number_format($productAddon->price, 2) }}
                                        </p>
                                        @if($productAddon->pivot->is_required)
                                            <span class="inline-block mt-1 px-2 py-1 bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-200 text-xs rounded font-medium">
                                                Required
                                            </span>
                                        @endif
                                    </div>
                                    <form action="{{ route('addons.detach', $productAddon) }}" method="POST" class="flex-shrink-0">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <button type="submit" onclick="return confirm('Remove this add-on from the product?')" class="p-1 text-red-500 hover:text-red-700 hover:bg-red-50 dark:hover:bg-zinc-700 rounded transition">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            @empty
                                <div class="p-6 text-center">
                                    <svg class="w-12 h-12 mx-auto text-gray-300 dark:text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                    </svg>
                                    <p class="text-gray-500 dark:text-gray-400 text-sm">
                                        No add-ons configured yet
                                    </p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add-On Configuration Modal -->
        <div id="addonModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
            <div class="bg-white dark:bg-zinc-800 rounded-lg shadow-xl max-w-md w-full">
                <div class="p-6 border-b border-gray-200 dark:border-zinc-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Configure: <span id="addonModalName"></span>
                    </h3>
                </div>

                <form id="addonForm" method="POST" class="p-6 space-y-4">
                    @csrf
                    <input type="hidden" id="addonId" name="addon_id">
                    <input type="hidden" name="product_id" value="{{ $product->id }}">

                    <div>
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" id="isRequired" name="is_required" class="w-4 h-4 text-blue-600 rounded focus:ring-2 focus:ring-blue-500">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Required Selection</span>
                        </label>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 ml-7">Customer must choose this add-on</p>
                    </div>

                    <div>
                        <label for="minSelection" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Minimum Selection
                        </label>
                        <input type="number" id="minSelection" name="min_selection" min="0" value="0" class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="maxSelection" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Maximum Selection
                        </label>
                        <input type="number" id="maxSelection" name="max_selection" min="0" value="1" class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div class="flex gap-3 pt-4">
                        <button type="button" onclick="closeAddOnModal()" class="flex-1 px-4 py-2 border border-gray-300 dark:border-zinc-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-zinc-700 transition font-medium">
                            Cancel
                        </button>
                        <button type="submit" class="flex-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition font-medium">
                            Add to Product
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <script>
            function openAddOnModal(addonId, addonName) {
                document.getElementById('addonId').value = addonId;
                document.getElementById('addonModalName').textContent = addonName;
                document.getElementById('isRequired').checked = false;
                document.getElementById('minSelection').value = 0;
                document.getElementById('maxSelection').value = 1;
                document.getElementById('addonModal').classList.remove('hidden');
            }

            function closeAddOnModal() {
                document.getElementById('addonModal').classList.add('hidden');
            }

            document.getElementById('addonForm').addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                const addonId = formData.get('addon_id');
                const productId = formData.get('product_id');
                
                fetch(`/api/products/${productId}/addons`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-Token': document.querySelector('input[name="_token"]').value
                    },
                    body: JSON.stringify({
                        addon_id: addonId,
                        is_required: formData.get('is_required') ? true : false,
                        min_selection: parseInt(formData.get('min_selection')),
                        max_selection: parseInt(formData.get('max_selection'))
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Error: ' + (data.message || 'Failed to add add-on'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error adding add-on to product');
                });
            });

            // Close modal when clicking outside
            document.getElementById('addonModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeAddOnModal();
                }
            });
        </script>
    </div>
</x-layouts::app>
