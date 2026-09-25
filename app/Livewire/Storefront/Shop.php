<?php

namespace App\Livewire\Storefront;

use App\Models\AgeGroup;
use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\Size;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Shop extends Component
{
    use WithPagination;

    public $search = '';
    public $category = '';
    public $age_group = '';
    public $size = '';
    public $color = '';
    public $sale = false;
    public $new_arrival = false;
    public $min_price = '';
    public $max_price = '';
    public $sort = 'featured';

    protected $queryString = [
        'search' => ['except' => ''],
        'category' => ['except' => ''],
        'age_group' => ['except' => ''],
        'size' => ['except' => ''],
        'color' => ['except' => ''],
        'sale' => ['except' => false],
        'new_arrival' => ['except' => false],
        'min_price' => ['except' => ''],
        'max_price' => ['except' => ''],
        'sort' => ['except' => 'featured'],
    ];

    public function updated($property)
    {
        if (in_array($property, ['search', 'category', 'age_group', 'size', 'color', 'sale', 'new_arrival', 'min_price', 'max_price', 'sort'])) {
            $this->resetPage();
        }
    }

    public function clearFilters()
    {
        $this->reset([
            'search',
            'category',
            'age_group',
            'size',
            'color',
            'sale',
            'new_arrival',
            'min_price',
            'max_price',
            'sort',
        ]);
        $this->resetPage();
    }

    public function removeFilter($key)
    {
        if ($key === 'sale' || $key === 'new_arrival') {
            $this->$key = false;
        } else {
            $this->$key = '';
        }
        $this->resetPage();
    }

    public function render()
    {
        // Query active products only
        $query = Product::with(['primaryImage', 'category', 'variants.size', 'variants.color'])
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
            
            // Build search variations (e.g. "0-3-months" -> ["0-3-months", "0-3", "0 to 3", "0-3m", "0-3 months"])
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

        // Size Filter (via product_variants)
        if (!empty($this->size)) {
            $sizeVal = $this->size;
            $query->whereHas('variants', function ($q) use ($sizeVal) {
                $q->where('status', true)
                    ->where(function ($sq) use ($sizeVal) {
                        $sq->where('size_id', $sizeVal)
                            ->orWhereHas('size', function ($ssq) use ($sizeVal) {
                                $ssq->where('name', $sizeVal);
                            });
                    });
            });
        }

        // Color Filter (via product_variants)
        if (!empty($this->color)) {
            $colorVal = $this->color;
            $query->whereHas('variants', function ($q) use ($colorVal) {
                $q->where('status', true)
                    ->where(function ($cq) use ($colorVal) {
                        $cq->where('color_id', $colorVal)
                            ->orWhereHas('color', function ($ccq) use ($colorVal) {
                                $ccq->where('name', $colorVal);
                            });
                    });
            });
        }

        // Sale Filter (sale_price < price AND is_sale/sale_price set)
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

        // Effective Price Filtering (Using pure Eloquent boolean logic)
        if ($this->min_price !== '' && is_numeric($this->min_price)) {
            $min = (float) $this->min_price;
            $query->where(function ($q) use ($min) {
                // Effective price is sale_price if sale_price is valid and < price
                $q->where(function ($sub) use ($min) {
                    $sub->whereNotNull('sale_price')
                        ->whereColumn('sale_price', '<', 'price')
                        ->where('sale_price', '>=', $min);
                })
                // Otherwise effective price is regular price
                ->orWhere(function ($sub) use ($min) {
                    $sub->where(function ($nullCheck) {
                        $nullCheck->whereNull('sale_price')
                            ->orWhereColumn('sale_price', '>=', 'price');
                    })
                    ->where('price', '>=', $min);
                });
            });
        }

        if ($this->max_price !== '' && is_numeric($this->max_price)) {
            $max = (float) $this->max_price;
            $query->where(function ($q) use ($max) {
                // Effective price is sale_price if sale_price is valid and < price
                $q->where(function ($sub) use ($max) {
                    $sub->whereNotNull('sale_price')
                        ->whereColumn('sale_price', '<', 'price')
                        ->where('sale_price', '<=', $max);
                })
                // Otherwise effective price is regular price
                ->orWhere(function ($sub) use ($max) {
                    $sub->where(function ($nullCheck) {
                        $nullCheck->whereNull('sale_price')
                            ->orWhereColumn('sale_price', '>=', 'price');
                    })
                    ->where('price', '<=', $max);
                });
            });
        }

        // Sorting
        $effectivePriceSql = 'CASE WHEN sale_price IS NOT NULL AND sale_price < price THEN sale_price ELSE price END';
        switch ($this->sort) {
            case 'price_low':
                $query->orderByRaw("{$effectivePriceSql} ASC");
                break;
            case 'price_high':
                $query->orderByRaw("{$effectivePriceSql} DESC");
                break;
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
