<?php

namespace App\Livewire\Storefront;

use App\Models\AgeGroup;
use App\Models\Category;
use App\Models\Collection;
use App\Models\Product;
use Livewire\Component;

class Home extends Component
{
    public function render()
    {
        // 1. Top-level active categories
        $categories = Category::whereNull('parent_id')
            ->where('status', true)
            ->orderBy('sort_order')
            ->get();

        // 2. New Arrivals (active products flagged as new_arrival)
        $newArrivals = Product::with(['primaryImage', 'category'])
            ->where('status', true)
            ->where('new_arrival', true)
            ->take(8)
            ->get();

        // 3. Featured Collection
        $featuredCollection = Collection::with(['products' => function ($query) {
            $query->where('status', true)->with(['primaryImage', 'category']);
        }])
            ->where('slug', 'featured')
            ->where('status', true)
            ->first();

        // 4. Age Groups
        $ageGroups = AgeGroup::where('status', true)
            ->orderBy('sort_order')
            ->get();

        // 5. Sale Products (active products with is_sale = true or sale_price < price)
        $saleProducts = Product::with(['primaryImage', 'category'])
            ->where('status', true)
            ->where(function ($query) {
                $query->where('is_sale', true)
                    ->orWhere(function ($sub) {
                        $sub->whereNotNull('sale_price')
                            ->whereColumn('sale_price', '<', 'price');
                    });
            })
            ->take(8)
            ->get();

        return view('livewire.storefront.home', [
            'categories' => $categories,
            'newArrivals' => $newArrivals,
            'featuredCollection' => $featuredCollection,
            'ageGroups' => $ageGroups,
            'saleProducts' => $saleProducts,
        ])->layout('components.layouts.storefront', [
            'title' => 'Al Hayat Kids | Premium Children\'s Fashion in Pakistan',
        ]);
    }
}
