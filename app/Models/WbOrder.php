<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WbOrder extends Model
{
    use HasFactory;
    protected $table = 'wb_orders';
    protected $fillable = [
        'g_number',
        'date',
        'last_change_date',
        'supplier_article',
        'tech_size',
        'discount_percent',
        'barcode',
        'oblast',
        'income_id',
        'total_price',
        'warehouse_name',
        'odid',
        'nm_id',
        'subject',
        'category',
        'brand',
        'is_cancel',
        'cancel_dt',
        'sticker',
        'srid',
    ];
}
