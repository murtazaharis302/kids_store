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

    // Bulk variant generator properties
    public $bulkSizes = [];
    public $bulkColors = [];
    public $bulkPrice = '';
    public $bulkSalePrice = '';
    public $bulkStock = 10;

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

    public function generateBulkVariants()
    {
        if (empty($this->bulkSizes) && empty($this->bulkColors)) {
            session()->flash('variant_error', 'Please select at least one Size or Color to generate variants.');
            return;
        }

        $sizes = !empty($this->bulkSizes) ? $this->bulkSizes : [null];
        $colors = !empty($this->bulkColors) ? $this->bulkColors : [null];

        // Remove default single initial variant if empty
        if (count($this->variants) === 1 && empty($this->variants[0]['size_id']) && empty($this->variants[0]['color_id'])) {
            $this->variants = [];
        }

        foreach ($colors as $colorId) {
            foreach ($sizes as $sizeId) {
                $exists = false;
                foreach ($this->variants as $v) {
                    if (($v['size_id'] ?? null) == $sizeId && ($v['color_id'] ?? null) == $colorId) {
                        $exists = true;
                        break;
                    }
                }
                if (!$exists) {
                    $this->variants[] = [
                        'size_id' => $sizeId ?: '',
                        'color_id' => $colorId ?: '',
                        'sku' => '',
                        'price' => $this->bulkPrice !== '' ? $this->bulkPrice : ($this->price ?: null),
                        'sale_price' => $this->bulkSalePrice !== '' ? $this->bulkSalePrice : ($this->sale_price ?: null),
                        'stock_quantity' => is_numeric($this->bulkStock) ? (int)$this->bulkStock : 10,
                        'status' => true,
                    ];
                }
            }
        }

        $this->generateVariantSKUs();
        $this->bulkSizes = [];
        $this->bulkColors = [];
    }

    public function generateVariantSKUs()
    {
        $baseSku = !empty($this->sku) ? $this->sku : 'AH-PROD';
        $sizes = Size::pluck('name', 'id');
        $colors = Color::pluck('name', 'id');

        $usedSkus = [];

        foreach ($this->variants as $index => $variant) {
            $sizeName = !empty($variant['size_id']) && isset($sizes[$variant['size_id']]) ? Str::slug($sizes[$variant['size_id']]) : '';
            $colorName = !empty($variant['color_id']) && isset($colors[$variant['color_id']]) ? Str::slug($colors[$variant['color_id']]) : '';

            $parts = array_filter([$baseSku, $colorName, $sizeName]);
            $proposedSku = strtoupper(implode('-', $parts));

            if (empty($proposedSku) || $proposedSku === strtoupper($baseSku)) {
                $proposedSku = strtoupper("{$baseSku}-V" . ($index + 1));
            }

            $finalSku = $proposedSku;
            $counter = 1;
            while (in_array($finalSku, $usedSkus)) {
                $finalSku = strtoupper("{$proposedSku}-{$counter}");
                $counter++;
            }

            $usedSkus[] = $finalSku;
            $this->variants[$index]['sku'] = $finalSku;

            if (!isset($this->variants[$index]['price']) || $this->variants[$index]['price'] === '') {
                $this->variants[$index]['price'] = $this->price ?: null;
            }
        }
    }

    public function removePrimaryImage()
    {
        $this->primaryImage = null;
    }

    public function removeNewAdditionalImage($index)
    {
        if (isset($this->additionalImages[$index])) {
            unset($this->additionalImages[$index]);
            $this->additionalImages = array_values($this->additionalImages);
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
            'primaryImage' => 'nullable|file|mimes:png,jpg,jpeg,webp,gif,svg,heic,heif,jfif,avif|max:20480', // 20MB max
            'additionalImages' => 'nullable|array',
            'additionalImages.*' => 'nullable|file|mimes:png,jpg,jpeg,webp,gif,svg,heic,heif,jfif,avif|max:20480',
            'variants' => 'array',
            'variants.*.size_id' => 'nullable',
            'variants.*.color_id' => 'nullable',
            'variants.*.sku' => 'nullable|string',
            'variants.*.price' => 'nullable|numeric|min:0',
            'variants.*.sale_price' => 'nullable|numeric|min:0',
            'variants.*.stock_quantity' => 'nullable|integer|min:0',
            'variants.*.status' => 'boolean',
        ];
    }

    public function save()
    {
        // Auto-generate SKUs for variants to prevent duplicate or missing SKUs
        $this->generateVariantSKUs();

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

        // Variants Save (or create default variant if none provided)
        $createdVariantsCount = 0;
        if (!empty($this->variants)) {
            foreach ($this->variants as $index => $variantData) {
                $variantSku = !empty($variantData['sku']) 
                    ? $variantData['sku'] 
                    : strtoupper($product->sku . '-V' . ($index + 1));

                ProductVariant::create([
                    'product_id' => $product->id,
                    'size_id' => !empty($variantData['size_id']) ? $variantData['size_id'] : null,
                    'color_id' => !empty($variantData['color_id']) ? $variantData['color_id'] : null,
                    'sku' => $variantSku,
                    'price' => !empty($variantData['price']) ? $variantData['price'] : null,
                    'sale_price' => !empty($variantData['sale_price']) ? $variantData['sale_price'] : null,
                    'stock_quantity' => isset($variantData['stock_quantity']) && $variantData['stock_quantity'] !== '' ? (int)$variantData['stock_quantity'] : 10,
                    'status' => isset($variantData['status']) ? (bool)$variantData['status'] : true,
                ]);
                $createdVariantsCount++;
            }
        }

        if ($createdVariantsCount === 0) {
            ProductVariant::create([
                'product_id' => $product->id,
                'size_id' => null,
                'color_id' => null,
                'sku' => strtoupper($product->sku . '-DEFAULT'),
                'price' => $product->price,
                'sale_price' => $product->sale_price,
                'stock_quantity' => 50,
                'status' => true,
            ]);
        }

        session()->flash('message', 'Product "' . $product->name . '" published successfully with ' . ProductVariant::where('product_id', $product->id)->count() . ' variants.');
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

