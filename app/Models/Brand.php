<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;

    protected $table = 'brands';

    public $timestamps = false;

    protected $fillable = ['brand_name', 'image', 'slug', 'status', 'module_id', 'items_count', 'brand_class', 'trademark', 'file', 'created_at', 'updated_at'];
}
