<?php

namespace App\Livewire\Storefront;

use App\Models\AgeGroup;
use App\Models\Category;
use App\Models\Collection;
use App\Models\Product;
use Livewire\Component;

class Home extends Component
{
    public $selectedCategorySlug = 'all';
    public $shuffleSeed = 1;

    public function selectCategory($slug)
    {
        $this->selectedCategorySlug = $slug;
    }

    public function shuffleProducts()
    {
        $this->shuffleSeed++;
    }

    public function render()
    {
        // 1. Top-level active categories with product count
        $categories = Category::whereNull('parent_id')
            ->where('status', true)
            ->withCount(['products' => function ($q) {
                $q->where('status', true);
            }])
            ->orderBy('sort_order')
            ->get();

        // 2. Category Showcase Products (Filtered by selected category tab & shuffled)
        $showcaseQuery = Product::with(['primaryImage', 'images', 'category'])
            ->where('status', true);

        if ($this->selectedCategorySlug !== 'all') {
            $catRecord = Category::where('slug', $this->selectedCategorySlug)->first();
            if ($catRecord) {
                $catIds = array_merge([$catRecord->id], $catRecord->children()->pluck('id')->toArray());
                $showcaseQuery->whereIn('category_id', $catIds);
            }
        }

        $showcaseProducts = $showcaseQuery->inRandomOrder($this->shuffleSeed)
            ->take(12)
            ->get();

        if ($showcaseProducts->isEmpty()) {
            $showcaseProducts = Product::with(['primaryImage', 'images', 'category'])
                ->where('status', true)
                ->latest()
                ->take(12)
                ->get();
        }

        // 3. New Arrivals (shuffled)
        $newArrivals = Product::with(['primaryImage', 'images', 'category'])
            ->where('status', true)
            ->where('new_arrival', true)
            ->inRandomOrder($this->shuffleSeed)
            ->take(8)
            ->get();

        if ($newArrivals->isEmpty()) {
            $newArrivals = Product::with(['primaryImage', 'images', 'category'])
                ->where('status', true)
                ->latest()
                ->take(8)
                ->get();
        }

        // 4. Featured Collection
        $featuredCollection = Collection::with(['products' => function ($query) {
            $query->where('status', true)->with(['primaryImage', 'images', 'category']);
        }])
            ->where('slug', 'featured')
            ->where('status', true)
            ->first();

        // 5. Age Groups
        $ageGroups = AgeGroup::where('status', true)
            ->orderBy('sort_order')
            ->get();

        // 6. Sale Products (shuffled)
        $saleProducts = Product::with(['primaryImage', 'images', 'category'])
            ->where('status', true)
            ->where(function ($query) {
                $query->where('is_sale', true)
                    ->orWhere(function ($sub) {
                        $sub->whereNotNull('sale_price')
                            ->whereColumn('sale_price', '<', 'price');
                    });
            })
            ->inRandomOrder($this->shuffleSeed)
            ->take(8)
            ->get();

        return view('livewire.storefront.home', [
            'categories' => $categories,
            'showcaseProducts' => $showcaseProducts,
            'newArrivals' => $newArrivals,
            'featuredCollection' => $featuredCollection,
            'ageGroups' => $ageGroups,
            'saleProducts' => $saleProducts,
        ])->layout('components.layouts.storefront', [
            'title' => 'Al Hayat Kids | Premium Children\'s Fashion in Pakistan',
        ]);
    }
}
