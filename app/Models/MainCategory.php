<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MainCategory extends Model
{
    use HasFactory;

    protected $table = 'ec_main_categories';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'layout_type',
        'is_home',
        'status',
    ];

    public function categories()
    {
        return $this->hasMany(Category::class, 'main_category_id');
    }
}
