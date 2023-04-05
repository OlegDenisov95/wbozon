<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WbPrice extends Model
{
    use HasFactory;
    protected $table = 'wb_prices';
    public $timestamps = false;
    protected $fillable = [
        'nm_id',
        'date',
        'price',
        'discount',
        'promo_code',
    ];
}
