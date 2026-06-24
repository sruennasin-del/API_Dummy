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

    /**
     * The colors that belong to the product.
     */
    public function colors(): BelongsToMany
    {
        return $this->belongsToMany(Color::class, 'ec_product_color', 'product_id', 'color_id')->withTimestamps();
    }

    /**
     * The sizes that belong to the product.
     */
    public function sizes(): BelongsToMany
    {
        return $this->belongsToMany(Size::class, 'ec_product_size', 'product_id', 'size_id')->withTimestamps();
    }

    /**
     * Get the gallery images for the product.
     */
    public function images(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ProductImage::class, 'product_id');
    }
}
