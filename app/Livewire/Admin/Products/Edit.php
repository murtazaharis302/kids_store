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
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class Edit extends Component
{
    use WithFileUploads;

    public Product $product;

    public $name;
    public $slug;
    public $sku;
    public $category_id;
    public $short_description;
    public $description;
    
    public $price;
    public $sale_price;
    public $cost_price;
    
    public $status;
    public $featured;
    public $new_arrival;
    public $is_sale;

    public $selectedAgeGroups = [];
    public $selectedCollections = [];

    public $primaryImage;
    public $additionalImages = [];

    public $existingImages = [];
    public $variants = [];

    // Bulk variant generator properties
    public $bulkSizes = [];
    public $bulkColors = [];
    public $bulkPrice = '';
    public $bulkSalePrice = '';
    public $bulkStock = 10;

    public function mount(Product $product)
    {
        $this->product = $product->load(['images', 'variants', 'ageGroups', 'collections']);

        $this->name = $product->name;
        $this->slug = $product->slug;
        $this->sku = $product->sku;
        $this->category_id = $product->category_id;
        $this->short_description = $product->short_description;
        $this->description = $product->description;

        $this->price = $product->price;
        $this->sale_price = $product->sale_price;
        $this->cost_price = $product->cost_price;

        $this->status = (bool) $product->status;
        $this->featured = (bool) $product->featured;
        $this->new_arrival = (bool) $product->new_arrival;
        $this->is_sale = (bool) $product->is_sale;

        $this->selectedAgeGroups = $product->ageGroups->pluck('id')->toArray();
        $this->selectedCollections = $product->collections->pluck('id')->toArray();

        $this->loadImagesAndVariants();
    }

    public function loadImagesAndVariants()
    {
        $this->existingImages = $this->product->images()->orderBy('sort_order')->get();

        $this->variants = [];
        foreach ($this->product->variants as $v) {
            $this->variants[] = [
                'id' => $v->id,
                'size_id' => $v->size_id ?: '',
                'color_id' => $v->color_id ?: '',
                'sku' => $v->sku,
                'price' => $v->price,
                'sale_price' => $v->sale_price,
                'stock_quantity' => $v->stock_quantity,
                'status' => (bool) $v->status,
            ];
        }

        if (empty($this->variants)) {
            $this->addVariant();
        }
    }

    public function updatedName($value)
    {
        $this->slug = Str::slug($value);
    }

    public function addVariant()
    {
        $this->variants[] = [
            'id' => null,
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
        $variantData = $this->variants[$index] ?? null;

        if ($variantData && !empty($variantData['id'])) {
            ProductVariant::destroy($variantData['id']);
        }

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

        // Remove single empty variant row if empty
        if (count($this->variants) === 1 && empty($this->variants[0]['id']) && empty($this->variants[0]['size_id']) && empty($this->variants[0]['color_id'])) {
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
                        'id' => null,
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
            if (!empty($variant['sku']) && !in_array($variant['sku'], $usedSkus)) {
                $usedSkus[] = $variant['sku'];
                continue;
            }

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

    public function deleteImage($imageId)
    {
        $img = ProductImage::find($imageId);
        if ($img && $img->product_id === $this->product->id) {
            if ($img->image && Storage::disk('public')->exists($img->image)) {
                Storage::disk('public')->delete($img->image);
            }
            $img->delete();
            $this->existingImages = $this->product->images()->orderBy('sort_order')->get();
        }
    }

    public function setPrimaryImage($imageId)
    {
        ProductImage::where('product_id', $this->product->id)->update(['is_primary' => false]);
        ProductImage::where('id', $imageId)->where('product_id', $this->product->id)->update(['is_primary' => true]);
        $this->existingImages = $this->product->images()->orderBy('sort_order')->get();
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
            'slug' => ['required', 'string', 'max:255', Rule::unique('products', 'slug')->ignore($this->product->id)],
            'sku' => ['required', 'string', 'max:255', Rule::unique('products', 'sku')->ignore($this->product->id)],
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
            'variants.*.id' => 'nullable',
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
        $this->generateVariantSKUs();

        $this->validate();

        $this->product->update([
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

        // Sync Age Groups & Collections
        $this->product->ageGroups()->sync($this->selectedAgeGroups);
        $this->product->collections()->sync($this->selectedCollections);

        // Upload New Primary Image if provided
        if ($this->primaryImage) {
            ProductImage::where('product_id', $this->product->id)->update(['is_primary' => false]);
            $path = $this->primaryImage->store('products', 'public');
            ProductImage::create([
                'product_id' => $this->product->id,
                'image' => $path,
                'alt_text' => $this->product->name . ' Primary Image',
                'is_primary' => true,
                'sort_order' => 1,
            ]);
        }

        // Upload New Gallery Images
        if (!empty($this->additionalImages)) {
            $lastSort = ProductImage::where('product_id', $this->product->id)->max('sort_order') ?: 1;
            foreach ($this->additionalImages as $img) {
                $path = $img->store('products', 'public');
                ProductImage::create([
                    'product_id' => $this->product->id,
                    'image' => $path,
                    'alt_text' => $this->product->name . ' Gallery Image',
                    'is_primary' => false,
                    'sort_order' => ++$lastSort,
                ]);
            }
        }

        // Save / Update Variants
        $activeCount = 0;
        if (!empty($this->variants)) {
            foreach ($this->variants as $index => $variantData) {
                $variantSku = !empty($variantData['sku']) 
                    ? $variantData['sku'] 
                    : strtoupper($this->sku . '-V' . ($index + 1));
                
                $stockQty = isset($variantData['stock_quantity']) && $variantData['stock_quantity'] !== '' ? (int)$variantData['stock_quantity'] : 10;

                if (!empty($variantData['id'])) {
                    ProductVariant::where('id', $variantData['id'])->update([
                        'size_id' => !empty($variantData['size_id']) ? $variantData['size_id'] : null,
                        'color_id' => !empty($variantData['color_id']) ? $variantData['color_id'] : null,
                        'sku' => $variantSku,
                        'price' => !empty($variantData['price']) ? $variantData['price'] : null,
                        'sale_price' => !empty($variantData['sale_price']) ? $variantData['sale_price'] : null,
                        'stock_quantity' => $stockQty,
                        'status' => $variantData['status'] ?? true,
                    ]);
                } else {
                    ProductVariant::create([
                        'product_id' => $this->product->id,
                        'size_id' => !empty($variantData['size_id']) ? $variantData['size_id'] : null,
                        'color_id' => !empty($variantData['color_id']) ? $variantData['color_id'] : null,
                        'sku' => $variantSku,
                        'price' => !empty($variantData['price']) ? $variantData['price'] : null,
                        'sale_price' => !empty($variantData['sale_price']) ? $variantData['sale_price'] : null,
                        'stock_quantity' => $stockQty,
                        'status' => $variantData['status'] ?? true,
                    ]);
                }
                $activeCount++;
            }
        }

        if ($activeCount === 0 && ProductVariant::where('product_id', $this->product->id)->count() === 0) {
            ProductVariant::create([
                'product_id' => $this->product->id,
                'size_id' => null,
                'color_id' => null,
                'sku' => strtoupper($this->sku . '-DEFAULT'),
                'price' => $this->price,
                'sale_price' => $this->sale_price,
                'stock_quantity' => 50,
                'status' => true,
            ]);
        }

        session()->flash('message', 'Product "' . $this->product->name . '" updated successfully.');
        return redirect()->route('admin.products.index');
    }

    public function render()
    {
        return view('livewire.admin.products.edit', [
            'categories' => Category::orderBy('name')->get(),
            'ageGroups' => AgeGroup::where('status', true)->orderBy('sort_order')->get(),
            'collections' => Collection::where('status', true)->orderBy('sort_order')->get(),
            'sizes' => Size::where('status', true)->orderBy('sort_order')->get(),
            'colors' => Color::where('status', true)->orderBy('name')->get(),
        ])->layout('components.layouts.admin', ['title' => 'Edit Product']);
    }
}
