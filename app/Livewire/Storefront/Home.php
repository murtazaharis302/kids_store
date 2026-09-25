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

        // 5. Age Groups (Strict Sequence)
        $targetAgeSlugs = [
            'newborn' => 'Newborn',
            '0-3-months' => '0 to 3 Months',
            '3-6-months' => '3 to 6 Months',
            '6-9-months' => '6 to 9 Months',
            '9-12-months' => '9 to 12 Months',
            '1-2-years' => '1 to 2 Years',
            '3-4-years' => '3 to 4 Years',
            '5-6-years' => '5 to 6 Years',
            '7-8-years' => '7 to 8 Years',
            '9-12-years' => '9 to 12 Years',
        ];

        $dbAgeGroups = AgeGroup::where('status', true)->get()->keyBy('slug');

        $ageGroups = collect($targetAgeSlugs)->map(function ($name, $slug) use ($dbAgeGroups) {
            if (isset($dbAgeGroups[$slug])) {
                return $dbAgeGroups[$slug];
            }
            return new AgeGroup([
                'name' => $name,
                'slug' => $slug,
                'status' => true,
            ]);
        })->values();

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
