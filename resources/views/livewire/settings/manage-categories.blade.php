<div class="space-y-4">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Categories</h2>
        <button wire:click="toggleForm" wire:loading.attr="disabled" class="bg-blue-600 hover:bg-blue-700 disabled:bg-blue-400 text-white px-4 py-2 rounded-lg flex items-center gap-2">
            <span wire:loading wire:target="toggleForm">
                <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </span>
            <span>{{ $showForm ? 'Cancel' : 'Add Category' }}</span>
        </button>
    </div>

    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
            {{ session('message') }}
        </div>
    @endif

    <!-- Add/Edit Form -->
    @if ($showForm)
        <div class="bg-white dark:bg-zinc-800 rounded-lg shadow p-6">
            <form wire:submit="save" class="space-y-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-900 dark:text-gray-100">
                        Category Name
                    </label>
                    <input
                        type="text"
                        id="name"
                        wire:model="name"
                        wire:loading.attr="disabled"
                        class="mt-1 block w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-zinc-700 px-3 py-2 text-gray-900 dark:text-white disabled:bg-gray-100 dark:disabled:bg-zinc-600"
                        placeholder="Enter category name"
                    />
                    @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="image" class="block text-sm font-medium text-gray-900 dark:text-gray-100">
                        Category Image (Optional)
                    </label>
                    <input
                        type="file"
                        id="image"
                        wire:model="image"
                        wire:loading.attr="disabled"
                        accept="image/*"
                        class="mt-1 block w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-zinc-700 px-3 py-2 text-gray-900 dark:text-white disabled:bg-gray-100 dark:disabled:bg-zinc-600"
                    />
                    @error('image') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

                    <div wire:loading wire:target="image" class="mt-2 flex items-center gap-2 text-blue-600">
                        <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span>Uploading image...</span>
                    </div>

                    @if ($image && is_object($image))
                        <div class="mt-2">
                            <img src="{{ $image->temporaryUrl() }}" alt="Preview" class="h-32 w-32 object-cover rounded">
                        </div>
                    @elseif ($editingId && $categories->firstWhere('id', $editingId)?->image)
                        <div class="mt-2">
                            <img src="{{ asset($categories->firstWhere('id', $editingId)->image) }}" alt="Current" class="h-32 w-32 object-cover rounded">
                        </div>
                    @endif
                </div>

                <button type="submit" wire:loading.attr="disabled" class="bg-green-600 hover:bg-green-700 disabled:bg-green-400 text-white px-4 py-2 rounded-lg flex items-center gap-2">
                    <span wire:loading wire:target="save">
                        <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </span>
                    <span>{{ $editingId ? 'Update Category' : 'Create Category' }}</span>
                </button>
            </form>
        </div>
    @endif

    <!-- Search -->
    <div class="flex gap-2 relative">
        <input
            type="text"
            wire:model.live="search"
            placeholder="Search categories..."
            class="flex-1 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-zinc-700 px-3 py-2 text-gray-900 dark:text-white"
        />
        <div wire:loading wire:target="search" class="absolute right-3 top-3">
            <svg class="animate-spin h-5 w-5 text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </div>
    </div>

    <!-- Categories Table -->
    <div class="overflow-x-auto bg-white dark:bg-zinc-800 rounded-lg shadow">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-zinc-700">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Image</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Slug</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Created</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse ($categories as $category)
                    <tr class="hover:bg-gray-50 dark:hover:bg-zinc-700">
                        <td class="px-6 py-4">
                            @if ($category->image)
                                <img src="{{ asset($category->image) }}" alt="{{ $category->name }}" class="h-12 w-12 object-cover rounded">
                            @else
                                <div class="h-12 w-12 bg-gray-200 dark:bg-gray-700 rounded flex items-center justify-center">
                                    <span class="text-xs text-gray-500">No image</span>
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">{{ $category->name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $category->slug }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                            <div>{{ $category->created_at->format('jS F, Y') }}</div>
                            <div class="text-xs text-gray-500">{{ $category->created_at->diffForHumans() }}</div>
                        </td>
                        <td class="px-6 py-4 text-sm font-medium space-x-2">
                            <button wire:click="edit({{ $category->id }})" wire:loading.attr="disabled" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 disabled:opacity-50 flex items-center gap-1">
                                <span wire:loading wire:target="edit({{ $category->id }})">
                                    <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </span>
                                Edit
                            </button>
                            <button wire:click="delete({{ $category->id }})" onclick="return confirm('Are you sure?')" wire:loading.attr="disabled" class="text-red-600 hover:text-red-900 dark:text-red-400 disabled:opacity-50 flex items-center gap-1">
                                <span wire:loading wire:target="delete({{ $category->id }})">
                                    <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </span>
                                Delete
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">No categories found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $categories->links() }}
    </div>
</div>
