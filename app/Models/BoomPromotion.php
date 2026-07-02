<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BoomPromotion extends Model
{
    protected $fillable = [
        'title',
        'subtitle',
        'description',
        'image',
        'shape',
        'link_url',
        'status',
    ];
}
