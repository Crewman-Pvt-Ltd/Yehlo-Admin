<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;

    protected $fillable = [
        'admin_landing',
        'delivery_partner',
    ];

    protected $casts = [
        'delivery_partner' => 'array',
    ];
}
