<?php

namespace App\Livewire\Storefront;

use App\Models\Product;
use Livewire\Component;

class ProductShow extends Component
{
    public Product $product;
    public $selectedImage = null;
    public $selectedColorId = null;
    public $selectedSizeId = null;
    public $selectedVariantId = null;
    public $quantity = 1;
    public $cartMessage = '';

    public function mount(Product $product)
    {
        // 1. Only active products may be viewed publicly
        if (!$product->status) {
            abort(404);
        }

        $this->product = $product->load([
            'primaryImage',
            'images',
            'category',
            'variants' => function ($q) {
                $q->where('status', true)->with(['size', 'color']);
            },
            'ageGroups',
        ]);

        // Default image
        if ($this->product->primaryImage) {
            $this->selectedImage = $this->product->primaryImage->image;
        } elseif ($this->product->images->isNotEmpty()) {
            $this->selectedImage = $this->product->images->first()->image;
        }

        // Auto-select first active variant if available
        $activeVariants = $this->product->variants;
        if ($activeVariants->isNotEmpty()) {
            $firstVariant = $activeVariants->first();
            $this->selectedVariantId = $firstVariant->id;
            $this->selectedColorId = $firstVariant->color_id;
            $this->selectedSizeId = $firstVariant->size_id;
        }
    }

    public function selectImage($imagePath)
    {
        $this->selectedImage = $imagePath;
    }

    public function selectColor($colorId)
    {
        $this->selectedColorId = $colorId;
        $this->autoSelectVariant();
    }

    public function selectSize($sizeId)
    {
        $this->selectedSizeId = $sizeId;
        $this->autoSelectVariant();
    }

    public function autoSelectVariant()
    {
        $activeVariants = $this->product->variants;

        // Try exact match first
        $variant = $activeVariants->first(function ($v) {
            $colorMatch = is_null($this->selectedColorId) ? is_null($v->color_id) : $v->color_id == $this->selectedColorId;
            $sizeMatch = is_null($this->selectedSizeId) ? is_null($v->size_id) : $v->size_id == $this->selectedSizeId;
            return $colorMatch && $sizeMatch;
        });

        // Fallback matching color if size combo unavailable
        if (!$variant && !is_null($this->selectedColorId)) {
            $variant = $activeVariants->firstWhere('color_id', $this->selectedColorId);
            if ($variant) {
                $this->selectedSizeId = $variant->size_id;
            }
        } elseif (!$variant && !is_null($this->selectedSizeId)) {
            $variant = $activeVariants->firstWhere('size_id', $this->selectedSizeId);
            if ($variant) {
                $this->selectedColorId = $variant->color_id;
            }
        }

        if ($variant) {
            $this->selectedVariantId = $variant->id;
            if ($this->quantity > $variant->stock_quantity && $variant->stock_quantity > 0) {
                $this->quantity = $variant->stock_quantity;
            } elseif ($variant->stock_quantity <= 0) {
                $this->quantity = 1;
            }
        } else {
            $this->selectedVariantId = null;
        }
    }

    public function incrementQuantity()
    {
        $stock = $this->getAvailableStockProperty();
        if ($this->quantity < $stock) {
            $this->quantity++;
        }
    }

    public function decrementQuantity()
    {
        if ($this->quantity > 1) {
            $this->quantity--;
        }
    }

    public function getSelectedVariantProperty()
    {
        if (!$this->selectedVariantId) {
            return null;
        }
        return $this->product->variants->firstWhere('id', $this->selectedVariantId);
    }

    public function getAvailableStockProperty()
    {
        $variant = $this->getSelectedVariantProperty();
        if ($variant) {
            return (int) $variant->stock_quantity;
        }
        if ($this->product->variants->isNotEmpty()) {
            return (int) $this->product->variants->max('stock_quantity');
        }
        return 50; // Fallback stock for simple products
    }

    public function getPricingProperty()
    {
        $variant = $this->getSelectedVariantProperty();

        if ($variant) {
            $regPrice = (float) ($variant->price ?: $this->product->price);
            $salePrice = !is_null($variant->sale_price) ? (float) $variant->sale_price : (!is_null($this->product->sale_price) ? (float) $this->product->sale_price : null);
            $hasValidSale = !is_null($salePrice) && $salePrice < $regPrice;

            return [
                'regular_price' => $regPrice,
                'sale_price' => $hasValidSale ? $salePrice : null,
                'has_sale' => $hasValidSale,
                'current_price' => $hasValidSale ? $salePrice : $regPrice,
            ];
        }

        $regPrice = (float) $this->product->price;
        $salePrice = !is_null($this->product->sale_price) ? (float) $this->product->sale_price : null;
        $hasValidSale = !is_null($salePrice) && $salePrice < $regPrice;

        return [
            'regular_price' => $regPrice,
            'sale_price' => $hasValidSale ? $salePrice : null,
            'has_sale' => $hasValidSale,
            'current_price' => $hasValidSale ? $salePrice : $regPrice,
        ];
    }

    public function addToCart()
    {
        $this->resetErrorBag();
        $this->cartMessage = '';

        $activeVariants = $this->product->variants;
        if ($activeVariants->isNotEmpty() && !$this->selectedVariantId) {
            $first = $activeVariants->first();
            $this->selectedVariantId = $first->id;
            $this->selectedColorId = $first->color_id;
            $this->selectedSizeId = $first->size_id;
        }

        $stock = $this->getAvailableStockProperty();
        if ($stock <= 0) {
            $this->addError('quantity', 'Selected option is currently out of stock.');
            return;
        }

        if ($this->quantity < 1) {
            $this->addError('quantity', 'Quantity must be at least 1.');
            return;
        }

        $result = \App\Services\CartService::addToCart($this->product->id, $this->selectedVariantId, (int) $this->quantity);

        if ($result['success']) {
            $this->cartMessage = $result['message'];
            $this->dispatch('cart-updated');
        } else {
            $this->addError('quantity', $result['message']);
        }
    }

    public function addToCartPlaceholder()
    {
        $this->addToCart();
    }

    public function render()
    {
        $activeVariants = $this->product->variants;

        // Unique available colors and sizes
        $availableColors = $activeVariants->pluck('color')->filter()->unique('id');
        $availableSizes = $activeVariants->pluck('size')->filter()->unique('id');

        // Valid size IDs for currently selected color
        $validSizeIdsForColor = [];
        if (!is_null($this->selectedColorId)) {
            $validSizeIdsForColor = $activeVariants
                ->where('color_id', $this->selectedColorId)
                ->pluck('size_id')
                ->filter()
                ->toArray();
        }

        // Valid color IDs for currently selected size
        $validColorIdsForSize = [];
        if (!is_null($this->selectedSizeId)) {
            $validColorIdsForSize = $activeVariants
                ->where('size_id', $this->selectedSizeId)
                ->pluck('color_id')
                ->filter()
                ->toArray();
        }

        // Related Products (same category preferred, active only, excluding current)
        $relatedProducts = Product::with(['primaryImage', 'category'])
            ->where('status', true)
            ->where('id', '!=', $this->product->id)
            ->where(function ($q) {
                if ($this->product->category_id) {
                    $q->where('category_id', $this->product->category_id);
                }
            })
            ->take(4)
            ->get();

        // Fallback related products if category has no other products
        if ($relatedProducts->count() < 4) {
            $existingIds = array_merge([$this->product->id], $relatedProducts->pluck('id')->toArray());
            $moreProducts = Product::with(['primaryImage', 'category'])
                ->where('status', true)
                ->whereNotIn('id', $existingIds)
                ->take(4 - $relatedProducts->count())
                ->get();
            $relatedProducts = $relatedProducts->concat($moreProducts);
        }

        return view('livewire.storefront.product-show', [
            'availableColors' => $availableColors,
            'availableSizes' => $availableSizes,
            'validSizeIdsForColor' => $validSizeIdsForColor,
            'validColorIdsForSize' => $validColorIdsForSize,
            'relatedProducts' => $relatedProducts,
        ])->layout('components.layouts.storefront', [
            'title' => "{$this->product->name} | AH Kids",
        ]);
    }
}
