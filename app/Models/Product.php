<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Fillable(['title', 'slug', 'category_id', 'price', 'stock', 'sales', 'rating', 'description', 'image', 'status'])]
class Product extends Model
{
    use HasFactory;

    protected $table = 'ec_products';

    /**
     * Get the category that the product belongs to.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'ec_product_category');
    }

    public function collections()
    {
        return $this->belongsToMany(Collection::class, 'ec_collection_product');
    }

    /**
     * The colors that belong to the product.
     */
    public function colors(): BelongsToMany
    {
        return $this->belongsToMany(Color::class, 'ec_product_color', 'product_id', 'color_id')
                    ->using(ProductColor::class)
                    ->withPivot('id', 'price')
                    ->withTimestamps();
    }

    /**
     * The specific color variants (with prices, sizes, images) that belong to this product.
     */
    public function colorVariants(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ProductColor::class, 'product_id');
    }

    /**
     * Backward compatibility accessor for sizes across all variants
     */
    public function getSizesAttribute()
    {
        $sizes = $this->colorVariants->flatMap->sizes->unique('id')->all();
        return new \Illuminate\Database\Eloquent\Collection($sizes);
    }

    /**
     * Backward compatibility accessor for images across all variants
     */
    public function getImagesAttribute()
    {
        $images = $this->colorVariants->flatMap->images->all();
        return new \Illuminate\Database\Eloquent\Collection($images);
    }
}
