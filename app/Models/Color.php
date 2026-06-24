<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'code', 'status'])]
class Color extends Model
{
    use HasFactory;

    protected $table = 'ec_colors';
}
