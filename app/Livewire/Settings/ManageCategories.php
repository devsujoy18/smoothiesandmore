<?php

namespace App\Livewire\Settings;

use App\Models\Category;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class ManageCategories extends Component
{
    use WithFileUploads, WithPagination;

    #[Url]
    public string $search = '';

    public string $name = '';

    public $image;

    public ?int $editingId = null;

    public bool $showForm = false;

    protected $rules = [
        'name' => 'required|string|max:255',
        'image' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:2048',
    ];

    public function render()
    {
        $categories = Category::query()
            ->when($this->search, function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                    ->orWhere('slug', 'like', "%{$this->search}%");
            })
            ->orderBy('name')
            ->paginate(10);

        return view('livewire.settings.manage-categories', [
            'categories' => $categories,
        ]);
    }

    public function resetForm()
    {
        $this->name = '';
        $this->image = null;
        $this->editingId = null;
        $this->showForm = false;
    }

    public function save()
    {
        $this->validate();

        $data = ['name' => $this->name];

        if ($this->image) {
            // Create directory if not exists
            $uploadPath = public_path('images/categories');
            if (! is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            $fileName = time().'_'.uniqid().'.'.$this->image->getClientOriginalExtension();
            $destinationPath = $uploadPath.'/'.$fileName;

            // Copy the file from temporary location to public folder
            copy($this->image->getRealPath(), $destinationPath);
            $data['image'] = 'images/categories/'.$fileName;
        }

        if ($this->editingId) {
            $category = Category::find($this->editingId);
            // Delete old image if exists and new one is uploaded
            if ($this->image && $category->image && file_exists(public_path($category->image))) {
                @unlink(public_path($category->image));
            }
            $category->update($data);
            session()->flash('message', 'Category updated successfully.');
        } else {
            Category::create($data);
            session()->flash('message', 'Category created successfully.');
        }

        $this->resetForm();
        $this->resetPage();
    }

    public function edit($id)
    {
        $category = Category::find($id);
        $this->editingId = $id;
        $this->name = $category->name;
        $this->image = null;
        $this->showForm = true;
    }

    public function delete($id)
    {
        $category = Category::find($id);
        if ($category->image && file_exists(public_path($category->image))) {
            @unlink(public_path($category->image));
        }
        $category->delete();
        session()->flash('message', 'Category deleted successfully.');
        $this->resetPage();
    }

    public function toggleForm()
    {
        $this->showForm = ! $this->showForm;
        if (! $this->showForm) {
            $this->resetForm();
        }
    }
}
