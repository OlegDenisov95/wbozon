<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WbStock extends Model
{
    use HasFactory;
    protected $table = 'wb_stocks';
    public $timestamps = false;
    protected $fillable = [
        'date',
        'last_change_date',
        'supplier_article',
        'tech_size',
        'barcode',
        'quantity',
        'is_supply',
        'is_realization',
        'quantity_not_in_orders',
        'quantity_full',
        'warehouse',
        'warehouse_name',
        'in_way_to_client',
        'in_way_from_client',
        'subject',
        'category',
        'days_on_site',
        'brand',
        'sc_code',
        'price',
        'discount',
        'nm_id',
    ];
}
