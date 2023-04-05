<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OzInfoStock extends Model
{
    use HasFactory;
    protected $table = 'oz_info_stocks';
    public $timestamps = false;
    protected $fillable = [
        'date',
        'product_id',
        'offer_id',
        'fbo_present',
        'fbo_reserved',
        'fbs_present',
        'fbs_reserved',
    ];
}
