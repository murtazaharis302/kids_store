<?php

namespace App\Livewire\Admin\Inventory;

use App\Models\Category;
use App\Models\Color;
use App\Models\ProductVariant;
use App\Models\Size;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $categoryFilter = '';
    public $sizeFilter = '';
    public $colorFilter = '';
    public $stockStatusFilter = '';

    public $adjustingVariantId = null;
    public $adjustmentAmount = 0;
    public $confirmingStockAdjustment = false;

    protected $queryString = [
        'search' => ['except' => ''],
        'categoryFilter' => ['except' => ''],
        'sizeFilter' => ['except' => ''],
        'colorFilter' => ['except' => ''],
        'stockStatusFilter' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCategoryFilter()
    {
        $this->resetPage();
    }

    public function updatingSizeFilter()
    {
        $this->resetPage();
    }

    public function updatingColorFilter()
    {
        $this->resetPage();
    }

    public function updatingStockStatusFilter()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->reset(['search', 'categoryFilter', 'sizeFilter', 'colorFilter', 'stockStatusFilter']);
        $this->resetPage();
    }

    public function openAdjustmentModal($id)
    {
        $this->authorizeAdminOrStaff();

        $variant = ProductVariant::with(['product', 'size', 'color'])->find($id);
        if ($variant) {
            $this->adjustingVariantId = $variant->id;
            $this->adjustmentAmount = 0;
            $this->resetErrorBag();
            $this->confirmingStockAdjustment = true;
        } else {
            session()->flash('error', 'Variant record not found.');
        }
    }

    public function cancelAdjustment()
    {
        $this->confirmingStockAdjustment = false;
        $this->adjustingVariantId = null;
        $this->adjustmentAmount = 0;
        $this->resetErrorBag();
    }

    public function applyStockAdjustment()
    {
        $this->authorizeAdminOrStaff();

        $this->validate([
            'adjustingVariantId' => 'required|exists:product_variants,id',
            'adjustmentAmount' => 'required|integer',
        ]);

        if ((int) $this->adjustmentAmount === 0) {
            $this->addError('adjustmentAmount', 'Adjustment amount must be a non-zero integer.');
            return;
        }

        try {
            $sku = '';
            $oldStock = 0;
            $newStock = 0;

            // Concurrency-safe authoritative transaction with pessimistic locking
            DB::transaction(function () use (&$sku, &$oldStock, &$newStock) {
                /** @var ProductVariant|null $variant */
                $variant = ProductVariant::lockForUpdate()->find($this->adjustingVariantId);
                if (!$variant) {
                    throw new \InvalidArgumentException('Variant record not found.');
                }

                $sku = $variant->sku;
                $oldStock = (int) $variant->stock_quantity;
                $calculatedStock = $oldStock + (int) $this->adjustmentAmount;

                if ($calculatedStock < 0) {
                    throw new \DomainException("Resulting stock quantity cannot be negative. Current stock is {$oldStock}.");
                }

                $newStock = $calculatedStock;
                $variant->update(['stock_quantity' => $newStock]);
            });

            session()->flash('message', "Stock for variant '{$sku}' updated from {$oldStock} to {$newStock}.");
            $this->cancelAdjustment();
        } catch (\DomainException $e) {
            $this->addError('adjustmentAmount', $e->getMessage());
        } catch (\Throwable $e) {
            session()->flash('error', 'Stock update failed: ' . $e->getMessage());
            $this->cancelAdjustment();
        }
    }

    protected function authorizeAdminOrStaff()
    {
        if (!auth()->check() || !in_array(auth()->user()->role, ['admin', 'staff'])) {
            abort(403, 'Unauthorized access to inventory management.');
        }
    }

    public function render()
    {
        $query = ProductVariant::with(['product.category', 'product.primaryImage', 'size', 'color']);

        if (!empty($this->search)) {
            $searchTerm = '%' . trim($this->search) . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('sku', 'like', $searchTerm)
                  ->orWhereHas('product', function ($pq) use ($searchTerm) {
                      $pq->where('name', 'like', $searchTerm);
                  });
            });
        }

        if ($this->categoryFilter !== '') {
            $query->whereHas('product', function ($q) {
                $q->where('category_id', $this->categoryFilter);
            });
        }

        if ($this->sizeFilter !== '') {
            $query->where('size_id', $this->sizeFilter);
        }

        if ($this->colorFilter !== '') {
            $query->where('color_id', $this->colorFilter);
        }

        if ($this->stockStatusFilter !== '') {
            if ($this->stockStatusFilter === 'in_stock') {
                $query->where('stock_quantity', '>', 15);
            } elseif ($this->stockStatusFilter === 'low_stock') {
                $query->where('stock_quantity', '>', 0)->where('stock_quantity', '<=', 15);
            } elseif ($this->stockStatusFilter === 'out_of_stock') {
                $query->where('stock_quantity', '<=', 0);
            }
        }

        $variants = $query->latest('id')->paginate(15);

        // Calculate Summary Metrics
        $totalVariants = ProductVariant::count();
        $totalStockUnits = (int) ProductVariant::sum('stock_quantity');
        $inStockCount = ProductVariant::where('stock_quantity', '>', 15)->count();
        $lowStockCount = ProductVariant::where('stock_quantity', '>', 0)->where('stock_quantity', '<=', 15)->count();
        $outOfStockCount = ProductVariant::where('stock_quantity', '<=', 0)->count();

        // Dropdown Filter Datasets
        $categories = Category::where('status', true)->orderBy('name')->get();
        $sizes = Size::where('status', true)->orderBy('sort_order')->get();
        $colors = Color::where('status', true)->orderBy('name')->get();

        $selectedVariantForAdjustment = $this->adjustingVariantId 
            ? ProductVariant::with(['product', 'size', 'color'])->find($this->adjustingVariantId)
            : null;

        return view('livewire.admin.inventory.index', [
            'variants' => $variants,
            'totalVariants' => $totalVariants,
            'totalStockUnits' => $totalStockUnits,
            'inStockCount' => $inStockCount,
            'lowStockCount' => $lowStockCount,
            'outOfStockCount' => $outOfStockCount,
            'categories' => $categories,
            'sizes' => $sizes,
            'colors' => $colors,
            'selectedVariantForAdjustment' => $selectedVariantForAdjustment,
        ])->layout('components.layouts.admin', ['title' => 'Inventory & Stock Management']);
    }
}
