<x-layouts::app :title="__('Add-ons')">
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Add-ons</h1>
        <a href="{{ route('addons.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
            Add New Add-on
        </a>
    </div>

    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
            {{ session('message') }}
        </div>
    @endif

    <!-- Search and Filter -->
    <div class="bg-white dark:bg-zinc-800 rounded-lg shadow p-4 space-y-4">
        <form method="GET" action="{{ route('addons.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search add-ons..." class="w-full border rounded px-3 py-2 dark:bg-zinc-700 dark:border-zinc-600 dark:text-white">
                </div>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Search</button>
                <a href="{{ route('addons.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded">Clear</a>
            </div>
        </form>
    </div>

    <!-- Add-ons Table -->
    <div class="overflow-x-auto bg-white dark:bg-zinc-800 rounded-lg shadow">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-zinc-700">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Image</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Price</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse ($addons as $addon)
                    <tr class="hover:bg-gray-50 dark:hover:bg-zinc-700">
                        <td class="px-6 py-4">
                            @if ($addon->image)
                                <img src="{{ asset($addon->image) }}" alt="{{ $addon->name }}" class="h-10 w-10 object-cover rounded">
                            @else
                                <div class="h-10 w-10 bg-gray-200 dark:bg-gray-700 rounded flex items-center justify-center">
                                    <span class="text-xs text-gray-500">No image</span>
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">{{ $addon->name }}</td>
                        <td class="px-6 py-4 text-sm font-semibold text-gray-900 dark:text-white">₹{{ number_format($addon->price, 2) }}</td>
                        <td class="px-6 py-4 text-sm">
                            <span class="px-3 py-1 rounded-full text-xs font-medium {{ $addon->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' }}">
                                {{ $addon->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm font-medium space-x-2">
                            <a href="{{ route('addons.edit', $addon) }}" class="text-purple-600 hover:text-purple-900 dark:text-purple-400">Edit</a>
                            <form action="{{ route('addons.destroy', $addon) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Are you sure?')" class="text-red-600 hover:text-red-900 dark:text-red-400">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">No add-ons found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $addons->links() }}
    </div>
</div>
</x-layouts::app>
