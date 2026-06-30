<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    protected $fillable = [
        'tag', 'title', 'subtitle', 'description',
        'btn_primary_label', 'btn_primary_url',
        'btn_secondary_label', 'btn_secondary_url',
        'image', 'bg_gradient', 'sort_order', 'status',
    ];

    public function scopeActive($query)
    {
        return $query->where('status', 'active')->orderBy('sort_order');
    }
}
