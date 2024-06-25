<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryFaq extends Model
{
    use HasFactory;

    // Define the table name
    protected $table = 'delivery_faq';

    // Define the fillable attributes
    protected $fillable = [
        'question',
        'anwser',
        'status'
    ];

    // Optionally, define the default attribute values
    protected $attributes = [
        'status' => 1,
    ];
}
