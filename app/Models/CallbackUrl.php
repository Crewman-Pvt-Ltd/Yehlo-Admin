<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CallbackUrl extends Model
{
    use HasFactory;

    protected $table = 'callback_url'; // Make sure this matches your table name

    protected $fillable = ['payload'];
}
