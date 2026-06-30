<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    use HasFactory;

    protected $table = 'ec_collections';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'status',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'ec_collection_product');
    }
}
