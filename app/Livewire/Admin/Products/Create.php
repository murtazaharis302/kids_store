<?php

namespace App\Livewire\Admin\Products;

use App\Models\AgeGroup;
use App\Models\Category;
use App\Models\Color;
use App\Models\Collection;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\Size;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class Create extends Component
{
    use WithFileUploads;

    public $name = '';
    public $slug = '';
    public $sku = '';
    public $category_id = '';
    public $short_description = '';
    public $description = '';
    
    public $price = '';
    public $sale_price = null;
    public $cost_price = null;
    
    public $status = true;
    public $featured = false;
    public $new_arrival = false;
    public $is_sale = false;

    public $selectedAgeGroups = [];
    public $selectedCollections = [];

    public $primaryImage;
    public $additionalImages = [];

    public $variants = [];

    public function updatedName($value)
    {
        $this->slug = Str::slug($value);
        if (empty($this->sku) && !empty($value)) {
            $this->sku = 'AH-' . strtoupper(Str::slug($value));
        }
    }

    public function mount()
    {
        // Add 1 default empty variant row
        $this->addVariant();
    }

    public function addVariant()
    {
        $this->variants[] = [
            'size_id' => '',
            'color_id' => '',
            'sku' => '',
            'price' => $this->price ?: null,
            'sale_price' => $this->sale_price ?: null,
            'stock_quantity' => 10,
            'status' => true,
        ];
    }

    public function removeVariant($index)
    {
        unset($this->variants[$index]);
        $this->variants = array_values($this->variants);
    }

    public function generateVariantSKUs()
    {
        $baseSku = !empty($this->sku) ? $this->sku : 'AH-PROD';
        $sizes = Size::pluck('name', 'id');
        $colors = Color::pluck('name', 'id');

        foreach ($this->variants as $index => $variant) {
            $sizeName = isset($sizes[$variant['size_id']]) ? Str::slug($sizes[$variant['size_id']]) : 'nosize';
            $colorName = isset($colors[$variant['color_id']]) ? Str::slug($colors[$variant['color_id']]) : 'nocolor';
            $this->variants[$index]['sku'] = strtoupper("{$baseSku}-{$colorName}-{$sizeName}");
            if (empty($this->variants[$index]['price'])) {
                $this->variants[$index]['price'] = $this->price ?: null;
            }
        }
    }

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:products,slug',
            'sku' => 'required|string|max:255|unique:products,sku',
            'category_id' => 'required|exists:categories,id',
            'short_description' => 'nullable|string|max:1000',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0|lt:price',
            'cost_price' => 'nullable|numeric|min:0',
            'status' => 'boolean',
            'featured' => 'boolean',
            'new_arrival' => 'boolean',
            'is_sale' => 'boolean',
            'selectedAgeGroups' => 'array',
            'selectedAgeGroups.*' => 'exists:age_groups,id',
            'selectedCollections' => 'array',
            'selectedCollections.*' => 'exists:collections,id',
            'primaryImage' => 'nullable|image|max:2048', // 2MB max
            'additionalImages.*' => 'nullable|image|max:2048',
            'variants' => 'array',
            'variants.*.size_id' => 'nullable|exists:sizes,id',
            'variants.*.color_id' => 'nullable|exists:colors,id',
            'variants.*.sku' => 'required|string|distinct',
            'variants.*.price' => 'nullable|numeric|min:0',
            'variants.*.sale_price' => 'nullable|numeric|min:0',
            'variants.*.stock_quantity' => 'required|integer|min:0',
            'variants.*.status' => 'boolean',
        ];
    }

    public function save()
    {
        $this->validate();

        $product = Product::create([
            'category_id' => $this->category_id,
            'name' => $this->name,
            'slug' => $this->slug,
            'sku' => $this->sku,
            'short_description' => $this->short_description,
            'description' => $this->description,
            'price' => $this->price,
            'sale_price' => $this->sale_price ?: null,
            'cost_price' => $this->cost_price ?: null,
            'status' => $this->status,
            'featured' => $this->featured,
            'new_arrival' => $this->new_arrival,
            'is_sale' => $this->is_sale,
        ]);

        // Attach Age Groups & Collections
        if (!empty($this->selectedAgeGroups)) {
            $product->ageGroups()->sync($this->selectedAgeGroups);
        }

        if (!empty($this->selectedCollections)) {
            $product->collections()->sync($this->selectedCollections);
        }

        // Primary Image Upload
        $sortOrder = 1;
        if ($this->primaryImage) {
            $path = $this->primaryImage->store('products', 'public');
            ProductImage::create([
                'product_id' => $product->id,
                'image' => $path,
                'alt_text' => $product->name . ' Primary Image',
                'is_primary' => true,
                'sort_order' => $sortOrder++,
            ]);
        }

        // Additional Images Upload
        if (!empty($this->additionalImages)) {
            foreach ($this->additionalImages as $img) {
                $path = $img->store('products', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image' => $path,
                    'alt_text' => $product->name . ' Gallery Image',
                    'is_primary' => false,
                    'sort_order' => $sortOrder++,
                ]);
            }
        }

        // Variants Save
        foreach ($this->variants as $variantData) {
            ProductVariant::create([
                'product_id' => $product->id,
                'size_id' => $variantData['size_id'] ?: null,
                'color_id' => $variantData['color_id'] ?: null,
                'sku' => $variantData['sku'],
                'price' => $variantData['price'] ?: null,
                'sale_price' => $variantData['sale_price'] ?: null,
                'stock_quantity' => $variantData['stock_quantity'],
                'status' => $variantData['status'] ?? true,
            ]);
        }

        session()->flash('message', 'Product "' . $product->name . '" created successfully.');
        return redirect()->route('admin.products.index');
    }

    public function render()
    {
        return view('livewire.admin.products.create', [
            'categories' => Category::orderBy('name')->get(),
            'ageGroups' => AgeGroup::where('status', true)->orderBy('sort_order')->get(),
            'collections' => Collection::where('status', true)->orderBy('sort_order')->get(),
            'sizes' => Size::where('status', true)->orderBy('sort_order')->get(),
            'colors' => Color::where('status', true)->orderBy('name')->get(),
        ])->layout('components.layouts.admin', ['title' => 'Create Product']);
    }
}
