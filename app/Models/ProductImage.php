<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'image',
        'alt_text',
        'is_primary',
        'sort_order',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get 100% fail-proof URL for the product image, bypassing cPanel symlink issues.
     */
    public function getUrlAttribute(): string
    {
        return static::getImageUrl($this->image);
    }

    /**
     * Resolve image URL for any given path or URL string.
     */
    public static function getImageUrl(?string $path): string
    {
        if (!$path) {
            return '';
        }

        if (\Illuminate\Support\Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        $cleanPath = ltrim($path, '/');

        // Check public folder directly
        if (file_exists(public_path($cleanPath))) {
            return asset($cleanPath);
        }

        // Check storage/app/public/ folder
        if (file_exists(storage_path('app/public/' . $cleanPath))) {
            $relative = \Illuminate\Support\Str::startsWith($cleanPath, 'products/') 
                ? substr($cleanPath, 9) 
                : $cleanPath;
            return url('/media/products/' . $relative);
        }

        // Fallback storage asset URL
        return asset('storage/' . $cleanPath);
    }
}
