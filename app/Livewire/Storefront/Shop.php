<?php

namespace App\Livewire\Storefront;

use App\Models\AgeGroup;
use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\Size;
use Livewire\Component;
use Livewire\WithPagination;

class Shop extends Component
{
    use WithPagination;

    // Query parameters reflected in URL
    public $search = '';
    public $category = '';
    public $age_group = '';
    public $size = '';
    public $color = '';
    public $sale = false;
    public $new_arrival = false;
    public $sort = 'featured';
    public $min_price = '';
    public $max_price = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'category' => ['except' => ''],
        'age_group' => ['except' => ''],
        'size' => ['except' => ''],
        'color' => ['except' => ''],
        'sale' => ['except' => false],
        'new_arrival' => ['except' => false],
        'sort' => ['except' => 'featured'],
        'min_price' => ['except' => ''],
        'max_price' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCategory()
    {
        $this->resetPage();
    }

    public function updatingAgeGroup()
    {
        $this->resetPage();
    }

    public function updatingSize()
    {
        $this->resetPage();
    }

    public function updatingColor()
    {
        $this->resetPage();
    }

    public function updatingSale()
    {
        $this->resetPage();
    }

    public function updatingNewArrival()
    {
        $this->resetPage();
    }

    public function updatingSort()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->reset(['search', 'category', 'age_group', 'size', 'color', 'sale', 'new_arrival', 'sort', 'min_price', 'max_price']);
        $this->resetPage();
    }

    public function removeFilter($filterName)
    {
        if (property_exists($this, $filterName)) {
            if (is_bool($this->$filterName)) {
                $this->$filterName = false;
            } else {
                $this->$filterName = '';
            }
            $this->resetPage();
        }
    }

    public function render()
    {
        $query = Product::with(['primaryImage', 'images', 'category', 'variants.size', 'variants.color'])
            ->where('status', true);

        // Search (Name, SKU, Description, or Variant SKU)
        if (!empty(trim($this->search))) {
            $searchTerm = '%' . trim($this->search) . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', $searchTerm)
                    ->orWhere('sku', 'like', $searchTerm)
                    ->orWhere('short_description', 'like', $searchTerm)
                    ->orWhereHas('variants', function ($vq) use ($searchTerm) {
                        $vq->where('sku', 'like', $searchTerm);
                    });
            });
        }

        // Category Filter (support slug or ID, including child categories)
        if (!empty($this->category)) {
            $catRecord = Category::where('slug', $this->category)
                ->orWhere('id', $this->category)
                ->first();

            if ($catRecord) {
                $categoryIds = array_merge([$catRecord->id], $catRecord->children()->pluck('id')->toArray());
                $query->whereIn('category_id', $categoryIds);
            }
        }

        // Age Group Filter (matches AgeGroup model relations & Variant Sizes flexibly)
        if (!empty($this->age_group)) {
            $ageVal = strtolower(trim($this->age_group));
            
            $keywords = [$ageVal];
            if (preg_match('/(\d+)[\s_\-]*to[\s_\-]*(\d+)/i', $ageVal, $m) || preg_match('/(\d+)[\s_\-]+(\d+)/i', $ageVal, $m)) {
                $n1 = $m[1];
                $n2 = $m[2];
                $keywords[] = "{$n1}-{$n2}";
                $keywords[] = "{$n1} to {$n2}";
                $keywords[] = "{$n1}-{$n2}m";
                $keywords[] = "{$n1}-{$n2}y";
                $keywords[] = "{$n1}-{$n2} months";
                $keywords[] = "{$n1}-{$n2} years";
            }

            $query->where(function ($q) use ($ageVal, $keywords) {
                $q->whereHas('ageGroups', function ($agq) use ($ageVal, $keywords) {
                    $agq->where('slug', $ageVal)
                        ->orWhere('age_groups.id', $ageVal);
                    foreach ($keywords as $kw) {
                        $agq->orWhere('slug', 'like', "%{$kw}%")
                            ->orWhere('name', 'like', "%{$kw}%");
                    }
                })->orWhereHas('variants', function ($vq) use ($keywords) {
                    $vq->where('status', true)
                        ->whereHas('size', function ($sq) use ($keywords) {
                            foreach ($keywords as $kw) {
                                $sq->orWhere('name', 'like', "%{$kw}%");
                            }
                        });
                });
            });
        }

        // Size Filter via Variants
        if (!empty($this->size)) {
            $sizeVal = strtolower(trim($this->size));
            $query->whereHas('variants', function ($q) use ($sizeVal) {
                $q->where('status', true)
                    ->whereHas('size', function ($sq) use ($sizeVal) {
                        $sq->where('slug', $sizeVal)
                            ->orWhere('code', $sizeVal)
                            ->orWhere('id', $sizeVal)
                            ->orWhere('name', 'like', "%{$sizeVal}%");
                    });
            });
        }

        // Color Filter via Variants
        if (!empty($this->color)) {
            $colorVal = strtolower(trim($this->color));
            $query->whereHas('variants', function ($q) use ($colorVal) {
                $q->where('status', true)
                    ->whereHas('color', function ($cq) use ($colorVal) {
                        $cq->where('slug', $colorVal)
                            ->orWhere('id', $colorVal)
                            ->orWhere('name', 'like', "%{$colorVal}%");
                    });
            });
        }

        // Sale Filter
        if ($this->sale) {
            $query->where(function ($q) {
                $q->where('is_sale', true)
                    ->orWhere(function ($sub) {
                        $sub->whereNotNull('sale_price')
                            ->whereColumn('sale_price', '<', 'price');
                    });
            });
        }

        // New Arrival Filter
        if ($this->new_arrival) {
            $query->where('new_arrival', true);
        }

        // Price Filter (Min / Max)
        if ($this->min_price !== '' && is_numeric($this->min_price)) {
            $minP = (float) $this->min_price;
            $query->where(function ($q) use ($minP) {
                $q->where(function ($sub) use ($minP) {
                    $sub->whereNotNull('sale_price')
                        ->whereColumn('sale_price', '<', 'price')
                        ->where('sale_price', '>=', $minP);
                })->orWhere(function ($sub) use ($minP) {
                    $sub->where(function ($s2) {
                        $s2->whereNull('sale_price')
                            ->orWhereColumn('sale_price', '>=', 'price');
                    })->where('price', '>=', $minP);
                });
            });
        }

        if ($this->max_price !== '' && is_numeric($this->max_price)) {
            $maxP = (float) $this->max_price;
            $query->where(function ($q) use ($maxP) {
                $q->where(function ($sub) use ($maxP) {
                    $sub->whereNotNull('sale_price')
                        ->whereColumn('sale_price', '<', 'price')
                        ->where('sale_price', '<=', $maxP);
                })->orWhere(function ($sub) use ($maxP) {
                    $sub->where(function ($s2) {
                        $s2->whereNull('sale_price')
                            ->orWhereColumn('sale_price', '>=', 'price');
                    })->where('price', '<=', $maxP);
                });
            });
        }

        // Sorting
        switch ($this->sort) {
            case 'price_low_high':
                $query->orderByRaw('CASE WHEN sale_price IS NOT NULL AND sale_price < price THEN sale_price ELSE price END ASC');
                break;
            case 'price_high_low':
                $query->orderByRaw('CASE WHEN sale_price IS NOT NULL AND sale_price < price THEN sale_price ELSE price END DESC');
                break;
            case 'latest':
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'shuffle':
            case 'random':
                $query->inRandomOrder();
                break;
            case 'featured':
            default:
                $query->orderBy('featured', 'desc')->orderBy('id', 'desc');
                break;
        }

        $products = $query->paginate(12);

        // Sidebar options from database
        $categories = Category::whereNull('parent_id')
            ->where('status', true)
            ->with(['children' => function ($q) {
                $q->where('status', true)->orderBy('sort_order');
            }])
            ->orderBy('sort_order')
            ->get();

        // Strict 11 Target Age Groups
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
            '9-10-years' => '9 to 10 Years',
            '11-12-years' => '11 to 12 Years',
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

        $sizes = Size::where('status', true)
            ->orderBy('sort_order')
            ->get();

        $colors = Color::where('status', true)
            ->get();

        return view('livewire.storefront.shop', [
            'products' => $products,
            'categories' => $categories,
            'ageGroups' => $ageGroups,
            'sizes' => $sizes,
            'colors' => $colors,
        ])->layout('components.layouts.storefront', [
            'title' => 'Shop All Products | AH Kids Pakistan',
        ]);
    }
}
