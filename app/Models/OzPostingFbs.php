<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OzPostingFbs extends Model
{
    use HasFactory;
    protected $table = 'oz_posting_fbs';
    protected $fillable = [
        "posting_number",
        "order_id",
        "order_number",
        "status",
        "tracking_number",
        "tpl_integration_type",
        "in_process_at",
        "shipment_date",
        "delivering_date",
        "customer",
        "addressee",
        "barcodes",
        "is_express",
        "parent_posting_number",
        "available_actions",
        "multi_box_qty",
        "is_multibox",
        'products_requiring_gtd',
        'products_requiring_country',
        'products_requiring_mandatory_mark',
        'products_requiring_rnpt',
        'commission_amount',
        'commission_percent',
        'payout',
        'product_id',
        'old_price',
        'price',
        'total_discount_value',
        'total_discount_percent',
        'actions',
        'picking',
        'quantity',
        'client_price',
        'currency_code',

        'marketplace_service_item_fulfillment',
        'marketplace_service_item_pickup',
        'marketplace_service_item_dropoff_pvz',
        'marketplace_service_item_dropoff_sc',
        'marketplace_service_item_dropoff_ff',
        'marketplace_service_item_direct_flow_trans',
        'marketplace_service_item_return_flow_trans',
        'marketplace_service_item_deliv_to_customer',
        'marketplace_service_item_return_not_deliv_to_customer',
        'marketplace_service_item_return_part_goods_customer',
        'marketplace_service_item_return_after_deliv_to_customer',
        'cluster_from',
        'cluster_to',
        'delivery_method_id',
        'delivery_method_name',
        'warehouse_id',
        'warehouse',
        'tpl_provider_id',
        'tpl_provider',


        'cancel_reason_id',
        'cancel_reason',
        'cancellation_type',
        'cancelled_after_ship',
        'affect_cancellation_rating',
        'cancellation_initiator',
        // 'price' ,
        'offer_id',
        'name',
        'sku',
        // 'quantity' ,
        'mandatory_mark',
        // 'currency_code' ,
        'region',
        'city',
        'delivery_type',
        'is_premium',
        'payment_type_group_name',
        'warehouse_id',
        'warehouse',
        'tpl_provider_id',
        'tpl_provider',
        'delivery_date_begin',
        'delivery_date_end',
        'is_legal',


    ];
}
