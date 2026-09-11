<?php

namespace App\Livewire\Admin\Products;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $categoryFilter = '';
    public $statusFilter = '';
    public $stockFilter = '';

    public $confirmingProductDeletion = false;
    public $productToDeleteId = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'categoryFilter' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'stockFilter' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCategoryFilter()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingStockFilter()
    {
        $this->resetPage();
    }

    public function confirmDelete($id)
    {
        $this->productToDeleteId = $id;
        $this->confirmingProductDeletion = true;
    }

    public function deleteProduct()
    {
        if (!$this->productToDeleteId) {
            return;
        }

        $product = Product::with(['images'])->find($this->productToDeleteId);

        if ($product) {
            // Delete physical image files from storage
            foreach ($product->images as $img) {
                if ($img->image && Storage::disk('public')->exists($img->image)) {
                    Storage::disk('public')->delete($img->image);
                }
            }

            $product->delete();
            session()->flash('message', 'Product and associated files deleted successfully.');
        }

        $this->confirmingProductDeletion = false;
        $this->productToDeleteId = null;
    }

    public function render()
    {
        $query = Product::with(['category', 'primaryImage', 'variants', 'images']);

        if (!empty($this->search)) {
            $searchTerm = '%' . trim($this->search) . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', $searchTerm)
                  ->orWhere('sku', 'like', $searchTerm);
            });
        }

        if ($this->categoryFilter !== '') {
            $query->where('category_id', $this->categoryFilter);
        }

        if ($this->statusFilter !== '') {
            $query->where('status', (bool) $this->statusFilter);
        }

        if ($this->stockFilter === 'low') {
            $query->whereHas('variants', function ($q) {
                $q->where('stock_quantity', '<=', 15);
            });
        } elseif ($this->stockFilter === 'out') {
            $query->whereHas('variants', function ($q) {
                $q->where('stock_quantity', '<=', 0);
            });
        }

        $products = $query->latest()->paginate(10);
        $categories = Category::whereNotNull('parent_id')->orWhereDoesntHave('children')->orderBy('name')->get();

        return view('livewire.admin.products.index', [
            'products' => $products,
            'categories' => $categories,
        ])->layout('components.layouts.admin', ['title' => 'Products Catalog']);
    }
}
