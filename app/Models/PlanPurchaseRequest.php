<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanPurchaseRequest extends Model
{
    use HasFactory;

    protected $table = 'plan_purchase_request';

    protected $fillable = [
        'vendor_id',
        'plan_id',
        'is_purchased',
        'mobile',
    ];
}
