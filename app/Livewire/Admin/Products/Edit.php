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
                'size_id' => $v->size_id,
                'color_id' => $v->color_id,
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

    public function deleteImage($imageId)
    {
        $img = ProductImage::find($imageId);
        if ($img && $img->product_id === $this->product->id) {
            // Delete physical file from storage
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
            'primaryImage' => 'nullable|image|max:2048',
            'additionalImages.*' => 'nullable|image|max:2048',
            'variants' => 'array',
            'variants.*.id' => 'nullable',
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
        foreach ($this->variants as $variantData) {
            if (!empty($variantData['id'])) {
                ProductVariant::where('id', $variantData['id'])->update([
                    'size_id' => $variantData['size_id'] ?: null,
                    'color_id' => $variantData['color_id'] ?: null,
                    'sku' => $variantData['sku'],
                    'price' => $variantData['price'] ?: null,
                    'sale_price' => $variantData['sale_price'] ?: null,
                    'stock_quantity' => $variantData['stock_quantity'],
                    'status' => $variantData['status'] ?? true,
                ]);
            } else {
                ProductVariant::create([
                    'product_id' => $this->product->id,
                    'size_id' => $variantData['size_id'] ?: null,
                    'color_id' => $variantData['color_id'] ?: null,
                    'sku' => $variantData['sku'],
                    'price' => $variantData['price'] ?: null,
                    'sale_price' => $variantData['sale_price'] ?: null,
                    'stock_quantity' => $variantData['stock_quantity'],
                    'status' => $variantData['status'] ?? true,
                ]);
            }
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
