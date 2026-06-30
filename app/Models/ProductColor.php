<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ProductColor extends Pivot
{
    use HasFactory;

    protected $table = 'ec_product_color';
    public $incrementing = true;

    protected $fillable = [
        'product_id',
        'color_id',
        'price',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function color()
    {
        return $this->belongsTo(Color::class);
    }

    public function sizes()
    {
        return $this->belongsToMany(Size::class, 'ec_product_size', 'product_color_id', 'size_id');
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class, 'product_color_id');
    }
}
