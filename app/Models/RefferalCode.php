<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefferalCode extends Model
{
    use HasFactory;


    protected $table = 'store_referral';


    protected $primaryKey = 'id';


    public $timestamps = false;


    protected $fillable = [
        'module_id',
        'zone_id',
        'referal_add_by',
        'referral_code',
        'created_at',
        'modified_at',
    ];


    protected $dates = [
        'created_at',
        'modified_at',
    ];


    protected $dateFormat = 'Y-m-d H:i:s';
}
