<?php

namespace App\Http\Controllers;

use App\Models\AddOn;
use Illuminate\Http\Request;

class AddOnController extends Controller
{
    /**
     * Display a listing of addons.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');

        $addons = AddOn::query()
            ->when($search, function ($q, $s) {
                $q->where('name', 'like', "%{$s}%");
            })
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        return view('addons.index', compact('addons', 'search'));
    }

    /**
     * Show the form for creating a new addon.
     */
    public function create()
    {
        return view('addons.create');
    }

    /**
     * Store a newly created addon in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:2048',
            'is_active' => 'boolean',
        ]);

        $data = [
            'name' => $validated['name'],
            'price' => $validated['price'],
            'is_active' => $validated['is_active'] ?? false,
        ];

        if ($request->hasFile('image')) {
            $uploadPath = public_path('images/addons');
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            $fileName = time() . '_' . uniqid() . '.' . $request->file('image')->getClientOriginalExtension();
            copy($request->file('image')->getRealPath(), $uploadPath . '/' . $fileName);
            $data['image'] = 'images/addons/' . $fileName;
        }

        AddOn::create($data);

        return redirect()->route('addons.index')->with('message', 'Add-on created successfully.');
    }

    /**
     * Show the form for editing the specified addon.
     */
    public function edit(AddOn $addon)
    {
        return view('addons.edit', compact('addon'));
    }

    /**
     * Update the specified addon in storage.
     */
    public function update(Request $request, AddOn $addon)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:2048',
            'is_active' => 'boolean',
        ]);

        $data = $validated;

        if ($request->hasFile('image')) {
            // Delete old image
            if ($addon->image && file_exists(public_path($addon->image))) {
                @unlink(public_path($addon->image));
            }

            $uploadPath = public_path('images/addons');
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            $fileName = time() . '_' . uniqid() . '.' . $request->file('image')->getClientOriginalExtension();
            copy($request->file('image')->getRealPath(), $uploadPath . '/' . $fileName);
            $data['image'] = 'images/addons/' . $fileName;
        }

        $addon->update($data);

        return redirect()->route('addons.index')->with('message', 'Add-on updated successfully.');
    }

    /**
     * Remove the specified addon from storage.
     */
    public function destroy(AddOn $addon)
    {
        if ($addon->image && file_exists(public_path($addon->image))) {
            @unlink(public_path($addon->image));
        }

        $addon->delete();

        return redirect()->route('addons.index')->with('message', 'Add-on deleted successfully.');
    }
}
