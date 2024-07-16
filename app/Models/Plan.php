<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $table = 'plan';

    protected $fillable = [
        'name',
        'type',
        'price',
        'product_limit',
        'desc_1',
        'desc_2',
        'desc_3',
        'mode',
        'status',
        'plan_duration',
        'add_by',
        'updated_by',
        'created_at',
        'updated_at',
    ];

    public function getCurrencySymbolAttribute()
    {
        $currency = Currency::where('currency_code', "INR")->first();
        if ($currency) {
            return $currency->currency_symbol;
        }
        // Return a default symbol if the currency is not found
        return '';
    }
}
