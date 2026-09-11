<?php

namespace App\Livewire\Admin\Categories;

use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class Edit extends Component
{
    use WithFileUploads;

    public Category $category;

    public $name = '';
    public $slug = '';
    public $parent_id = null;
    public $description = '';
    public $newImage = null;
    public $status = true;
    public $sort_order = 0;
    public $existingImage = null;

    public function mount(Category $category)
    {
        $this->category = $category;
        $this->name = $category->name;
        $this->slug = $category->slug;
        $this->parent_id = $category->parent_id;
        $this->description = $category->description ?? '';
        $this->status = (bool) $category->status;
        $this->sort_order = (int) $category->sort_order;
        $this->existingImage = $category->image;
    }

    // ON EDIT: Do NOT automatically overwrite slug when name changes.
    // Admin can edit slug explicitly if desired.

    protected function getExcludedParentIds()
    {
        $excluded = [$this->category->id];
        return array_merge($excluded, $this->getDescendantIds($this->category));
    }

    protected function getDescendantIds(Category $category)
    {
        $ids = [];
        foreach ($category->children as $child) {
            $ids[] = $child->id;
            $ids = array_merge($ids, $this->getDescendantIds($child));
        }
        return $ids;
    }

    protected function rules()
    {
        $excludedParentIds = implode(',', $this->getExcludedParentIds());

        return [
            'name' => 'required|string|max:255',
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories', 'slug')->ignore($this->category->id),
            ],
            'parent_id' => [
                'nullable',
                'exists:categories,id',
                "not_in:{$excludedParentIds}",
            ],
            'description' => 'nullable|string',
            'newImage' => 'nullable|image|max:2048',
            'status' => 'boolean',
            'sort_order' => 'required|integer|min:0',
        ];
    }

    protected $messages = [
        'name.required' => 'Category name is required.',
        'slug.required' => 'Category slug is required.',
        'slug.unique' => 'This category slug is already taken by another category.',
        'parent_id.not_in' => 'Invalid parent selection. A category cannot be its own parent or select a descendant.',
        'newImage.image' => 'The uploaded file must be an image.',
        'newImage.max' => 'The image size cannot exceed 2MB.',
    ];

    public function removeCurrentImage()
    {
        if ($this->existingImage) {
            $this->cleanupPhysicalImage($this->existingImage);
            $this->existingImage = null;
            $this->category->update(['image' => null]);
            session()->flash('message', 'Category image removed.');
        }
    }

    protected function cleanupPhysicalImage($imagePath)
    {
        if (!$imagePath) {
            return;
        }

        // Do NOT delete image file if another category is still referencing it
        $usedByOther = Category::where('id', '!=', $this->category->id)
            ->where('image', $imagePath)
            ->exists();

        if (!$usedByOther && Storage::disk('public')->exists($imagePath)) {
            Storage::disk('public')->delete($imagePath);
        }
    }

    public function update()
    {
        $validatedData = $this->validate();

        $imagePath = $this->existingImage;

        if ($this->newImage) {
            // Delete old physical image if replaced and not shared
            if ($this->existingImage) {
                $this->cleanupPhysicalImage($this->existingImage);
            }
            $imagePath = $this->newImage->store('categories', 'public');
        }

        $this->category->update([
            'name' => $this->name,
            'slug' => Str::slug($this->slug),
            'parent_id' => $this->parent_id ?: null,
            'description' => $this->description,
            'image' => $imagePath,
            'status' => (bool) $this->status,
            'sort_order' => (int) $this->sort_order,
        ]);

        session()->flash('message', "Category '{$this->name}' updated successfully.");

        return redirect()->route('admin.categories.index');
    }

    public function render()
    {
        $excludedIds = $this->getExcludedParentIds();
        $parentCategories = Category::whereNotIn('id', $excludedIds)->orderBy('name')->get();

        return view('livewire.admin.categories.edit', [
            'parentCategories' => $parentCategories,
        ])->layout('components.layouts.admin', ['title' => 'Edit Category']);
    }
}
