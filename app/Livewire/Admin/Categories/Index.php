<?php

namespace App\Livewire\Admin\Categories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $parentFilter = '';

    public $confirmingCategoryDeletion = false;
    public $categoryToDeleteId = null;
    public $categoryToDeleteName = '';
    public $deleteError = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'parentFilter' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingParentFilter()
    {
        $this->resetPage();
    }

    public function confirmDelete($id)
    {
        $category = Category::find($id);
        if ($category) {
            $this->categoryToDeleteId = $category->id;
            $this->categoryToDeleteName = $category->name;
            $this->deleteError = null;
            $this->confirmingCategoryDeletion = true;
        }
    }

    public function cancelDelete()
    {
        $this->confirmingCategoryDeletion = false;
        $this->categoryToDeleteId = null;
        $this->categoryToDeleteName = '';
        $this->deleteError = null;
    }

    public function deleteCategory()
    {
        if (!$this->categoryToDeleteId) {
            return;
        }

        $category = Category::find($this->categoryToDeleteId);

        if (!$category) {
            $this->cancelDelete();
            return;
        }

        // Fresh Database Checks immediately before deletion
        $productsCount = Product::where('category_id', $category->id)->count();
        if ($productsCount > 0) {
            $this->deleteError = "This category cannot be deleted because it contains {$productsCount} product(s). Move or remove those products first.";
            return;
        }

        $childrenCount = Category::where('parent_id', $category->id)->count();
        if ($childrenCount > 0) {
            $this->deleteError = "This category cannot be deleted because it has {$childrenCount} child subcategory/subcategories. Handle or remove child categories first.";
            return;
        }

        // Clean up physical image file if present and not used by another category
        if ($category->image) {
            $otherCategoryUsingImage = Category::where('id', '!=', $category->id)
                ->where('image', $category->image)
                ->exists();

            if (!$otherCategoryUsingImage && Storage::disk('public')->exists($category->image)) {
                Storage::disk('public')->delete($category->image);
            }
        }

        $categoryName = $category->name;
        $category->delete();

        session()->flash('message', "Category '{$categoryName}' deleted successfully.");

        $this->cancelDelete();
    }

    public function render()
    {
        $query = Category::with(['parent'])->withCount(['products', 'children']);

        if (!empty($this->search)) {
            $searchTerm = '%' . trim($this->search) . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', $searchTerm)
                  ->orWhere('slug', 'like', $searchTerm)
                  ->orWhere('description', 'like', $searchTerm);
            });
        }

        if ($this->statusFilter !== '') {
            $query->where('status', (bool) $this->statusFilter);
        }

        if ($this->parentFilter === 'main') {
            $query->whereNull('parent_id');
        } elseif ($this->parentFilter === 'sub') {
            $query->whereNotNull('parent_id');
        } elseif ($this->parentFilter !== '') {
            $query->where('parent_id', $this->parentFilter);
        }

        $categories = $query->latest()->paginate(10);
        $parentCategoriesList = Category::whereNull('parent_id')->orderBy('name')->get();

        return view('livewire.admin.categories.index', [
            'categories' => $categories,
            'parentCategoriesList' => $parentCategoriesList,
        ])->layout('components.layouts.admin', ['title' => 'Categories Catalog']);
    }
}
