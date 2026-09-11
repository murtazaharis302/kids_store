<?php

namespace App\Livewire\Admin\Categories;

use App\Models\Category;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class Create extends Component
{
    use WithFileUploads;

    public $name = '';
    public $slug = '';
    public $parent_id = null;
    public $description = '';
    public $image = null;
    public $status = true;
    public $sort_order = 0;

    public $manualSlug = false;

    public function updatedName($value)
    {
        if (!$this->manualSlug) {
            $this->slug = Str::slug($value);
        }
    }

    public function updatedSlug()
    {
        $this->manualSlug = true;
    }

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:categories,slug',
            'parent_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'status' => 'boolean',
            'sort_order' => 'required|integer|min:0',
        ];
    }

    protected $messages = [
        'name.required' => 'Category name is required.',
        'slug.required' => 'Category slug is required.',
        'slug.unique' => 'This category slug is already taken.',
        'image.image' => 'The uploaded file must be an image.',
        'image.max' => 'The image size cannot exceed 2MB.',
    ];

    public function save()
    {
        if (empty($this->slug) && !empty($this->name)) {
            $this->slug = Str::slug($this->name);
        }

        $validatedData = $this->validate();

        $imagePath = null;
        if ($this->image) {
            $imagePath = $this->image->store('categories', 'public');
        }

        Category::create([
            'name' => $this->name,
            'slug' => Str::slug($this->slug),
            'parent_id' => $this->parent_id ?: null,
            'description' => $this->description,
            'image' => $imagePath,
            'status' => (bool) $this->status,
            'sort_order' => (int) $this->sort_order,
        ]);

        session()->flash('message', "Category '{$this->name}' created successfully.");

        return redirect()->route('admin.categories.index');
    }

    public function render()
    {
        // Only main categories (or root level) should be selectable as parent categories to keep neat 2-tier tree if desired
        $parentCategories = Category::orderBy('name')->get();

        return view('livewire.admin.categories.create', [
            'parentCategories' => $parentCategories,
        ])->layout('components.layouts.admin', ['title' => 'Create Category']);
    }
}
