<?php

namespace App\Http\Controllers;

use DateInterval;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    //
    public function index()
    {
        //  (new DateTime())->format('c')]
        // $response = Http::withHeaders([
        //     'Authorization'=>config('services.wb.statistic_key')
        // ])->get('https://statistics-api.wildberries.ru/api/v1/supplier/incomes',['dateFrom'=>'2023-03-28']);

        // foreach($price->json() as $row)
        // {
        //     if(DB::$table(''))
        // }
        $date = new DateTime('-4 day');
        //    $this->fetchPrices();
        //    $this->fetchStocks($date);
        //    $this->fetchIncomes($date);
        //    $this->fetchOrders($date);
        //    $this->fetchSales($date);
        //    $this->fetchSalesReports($date);
        // $this->fetchOzon();
        $this->fetchOzonPosting($date);
        $this->fetchOzonPostingFbo($date);
        // $this->fetchWarehouses();
        return 'do';
    }
    protected function fetchPrices()
    {
        $response = Http::withHeaders([
            'Authorization' => config('services.wb.standard_key')
        ])->get('https://suppliers-api.wildberries.ru/public/api/v1/info');
        foreach ($response->json() as $row) {
            if (!DB::table('wb_prices')->where('nm_id', $row['nmId'])->exists()) {
                DB::table('wb_prices')->insert([
                    'nm_id' => $row['nmId'],
                    'date' => (new DateTime())->format('Y-m-d'),
                    'price' => $row['price'],
                    'discount' => $row['discount'],
                    'promo_code' => $row['promoCode'],
                ]);
            }
        }
    }
    protected function fetchStocks(DateTime $dateForm)
    {
        $response = Http::withHeaders([
            'Authorization' => config('services.wb.statistic_key')
        ])->get('https://statistics-api.wildberries.ru/api/v1/supplier/stocks', ['dateFrom' => $dateForm->format('Y-m-d')]);
        foreach ($response->json() as $row) {
            if (!DB::table('wb_stocks')->where([
                'barcode' => $row['barcode'], 'warehouse_name' => $row['warehouseName']
            ])->exists())
                DB::table('wb_stocks')->insert([
                    'date' => (new DateTime())->format('Y-m-d'),
                    'last_change_date' => $row['lastChangeDate'],
                    'supplier_article' => $row['supplierArticle'],
                    'tech_size' => $row['techSize'],
                    'barcode' => $row['barcode'],
                    'quantity' => $row['quantity'],
                    'is_supply' => $row['isSupply'],
                    'is_realization' => $row['isRealization'],
                    'quantity_not_in_orders' => $row['quantityFull'],
                    'quantity_full' => $row['quantityFull'],
                    'warehouse' => $row['quantityFull'],
                    'warehouse_name' => $row['warehouseName'],
                    'in_way_to_client' => $row['quantityFull'],
                    'in_way_from_client' => $row['quantityFull'],
                    'subject' => $row['subject'],
                    'category' => $row['category'],
                    'days_on_site' => $row['daysOnSite'],
                    'brand' => $row['brand'],
                    'sc_code' => $row['SCCode'],
                    'price' => $row['Price'],
                    'discount' => $row['Discount'],
                    'nm_id' => $row['nmId'],
                ]);
        }
    }

    protected function fetchIncomes(DateTime $dateForm)
    {
        $response = Http::withHeaders([
            'Authorization' => config('services.wb.statistic_key')
        ])->get('https://statistics-api.wildberries.ru/api/v1/supplier/incomes', ['dateFrom' => $dateForm->format('Y-m-d')]);
        //dd($response->json());
        foreach ($response->json() as $row) {

            if (!DB::table('wb_incomes')->where([
                'income_id' => $row['incomeId'],
                'barcode' => $row['barcode']
            ])->exists()) {
                DB::table('wb_incomes')->insert([
                    'income_id' => $row['incomeId'],
                    'number' => $row['number'],
                    'date' => $row['date'],
                    'last_change_date' => $row['lastChangeDate'],
                    'supplier_article' => $row['supplierArticle'],
                    'tech_size' => $row['techSize'],
                    'barcode' => $row['barcode'],
                    'quantity' => $row['quantity'],
                    'total_price' => $row['totalPrice'],
                    'date_close' => $row['dateClose'],
                    'warehouse_name' => $row['warehouseName'],
                    'nm_id' => $row['nmId'],
                    'status' => $row['status'],
                ]);
            }
        }
    }


    protected function fetchOrders(DateTime $dateForm)
    {
        $response = Http::withHeaders([
            'Authorization' => config('services.wb.statistic_key')
        ])->get('https://statistics-api.wildberries.ru/api/v1/supplier/orders', ['dateFrom' => $dateForm->format('Y-m-d'), 'flag' => 1]);
        //dd($response->json());
        foreach ($response->json() as $row) {
            if (!DB::table('wb_orders')->where('odid', $row['odid'])->exists()) {
                DB::table('wb_orders')->insert([
                    'g_number' => $row['gNumber'],
                    'date' => $row['date'],
                    'last_change_date' => $row['lastChangeDate'],
                    'supplier_article' => $row['supplierArticle'],
                    'tech_size' => $row['techSize'],
                    'discount_percent' => $row['discountPercent'],
                    'barcode' => $row['barcode'],
                    'oblast' => $row['oblast'],
                    'income_id' => $row['incomeID'],
                    'total_price' => $row['totalPrice'],
                    'warehouse_name' => $row['warehouseName'],
                    'odid' => $row['odid'],
                    'nm_id' => $row['nmId'],
                    'subject' => $row['subject'],
                    'category' => $row['category'],
                    'brand' => $row['brand'],
                    'is_cancel' => $row['isCancel'],
                    'cancel_dt' => $row['cancel_dt'],
                    'sticker' => $row['sticker'],
                    'srid' => $row['srid'],
                ]);
            }
        }
    }
    protected function fetchSales(DateTime $dateForm)
    {
        $response = Http::withHeaders([
            'Authorization' => config('services.wb.statistic_key')
        ])->get('https://statistics-api.wildberries.ru/api/v1/supplier/sales', ['dateFrom' => $dateForm->format('Y-m-d')]);
        //dd($response->json());
        foreach ($response->json() as $row) {
            if (!DB::table('wb_sales')->where('sale_id', $row['saleID'])->exists()) {
                DB::table('wb_sales')->insert([
                    'g_number' => $row['gNumber'],
                    'date' => $row['date'],
                    'last_change_date' => $row['lastChangeDate'],
                    'supplier_article' => $row['supplierArticle'],
                    'tech_size' => $row['techSize'],
                    'barcode' => $row['barcode'],
                    'total_price' => $row['totalPrice'],
                    'discount_percent' => $row['discountPercent'],
                    'is_supply' => $row['isSupply'],
                    'is_realization' => $row['isRealization'],
                    'promo_code_discount' => $row['promoCodeDiscount'],
                    'warehouse_name' => $row['warehouseName'],

                    'country_name' => $row['countryName'],
                    'oblast_okrug_name' => $row['oblastOkrugName'],

                    'region_name' => $row['regionName'],
                    'income_id' => $row['incomeID'],
                    'sale_id' => $row['saleID'],
                    'sale_id_status' => $row['category'],
                    'odid' => $row['odid'],
                    'spp' => $row['spp'],
                    'for_pay' => $row['forPay'],
                    'finished_price' => $row['finishedPrice'],
                    'price_with_disc' => $row['priceWithDisc'],
                    'nm_id' => $row['nmId'],
                    'subject' => $row['subject'],
                    'category' => $row['category'],
                    'brand' => $row['brand'],
                    'is_storno' => $row['IsStorno'],
                    'sticker' => $row['sticker'],
                    'srid' => $row['srid'],
                ]);
            }
        }
    }
    protected function fetchSalesReports(DateTime $dateForm)
    {
        $response = Http::withHeaders([
            'Authorization' => config('services.wb.statistic_key')
        ])->get('https://statistics-api.wildberries.ru/api/v1/supplier/reportDetailByPeriod', [
            'dateFrom' => $dateForm->format('Y-m-d'), 'limit' => 10000,
            'dateTo' => $dateForm->add(new DateInterval('P2D'))->format('Y-m-d'),
            'rrdid' => 0
        ]);
        // dd($response->json());
        $rows = $response->json();
        if (!isset($rows)) {
            return;
        }
        do {

            foreach ($rows as $row) {
                if (!DB::table('wb_sales_reports')->where('rrd_id', $row['rrd_id'])->exists()) {
                    // dd($row['rid']);
                    DB::table('wb_sales_reports')->insert([
                        'realizationreport_id' => $row['realizationreport_id'],
                        'date_from' => (new DateTime($row['date_from']))->format('Y-m-d'),
                        'date_to' => (new DateTime($row['date_to']))->format('Y-m-d'),
                        'create_dt' => (new DateTime($row['create_dt']))->format('Y-m-d'),
                        'suppliercontract_code' => $row['suppliercontract_code'],
                        'rrd_id' => $row['rrd_id'],
                        'gi_id' => $row['gi_id'],
                        'subject_name' => $row['subject_name'],
                        'nm_id' => $row['nm_id'],
                        'brand_name' => $row['brand_name'],
                        'sa_name' => $row['sa_name'],
                        'ts_name' => $row['ts_name'],

                        'barcode' => $row['barcode'],
                        'doc_type_name' => $row['doc_type_name'],

                        'quantity' => $row['quantity'],
                        'retail_price' => $row['retail_price'],
                        'retail_amount' => $row['retail_amount'],
                        'sale_percent' => $row['sale_percent'],
                        'commission_percent' => $row['commission_percent'],
                        'office_name' => $row['office_name'],
                        'supplier_oper_name' => $row['supplier_oper_name'],
                        'order_dt' => (new DateTime($row['order_dt']))->format('Y-m-d'),
                        'sale_dt' => (new DateTime($row['sale_dt']))->format('Y-m-d'),
                        'rr_dt' => (new DateTime($row['rr_dt']))->format('Y-m-d'),
                        'shk_id' => $row['shk_id'],
                        'retail_price_withdisc_rub' => $row['retail_price_withdisc_rub'],
                        'delivery_amount' => $row['delivery_amount'],
                        'return_amount' => $row['return_amount'],
                        'delivery_rub' => $row['delivery_rub'],
                        'gi_box_type_name' => $row['gi_box_type_name'],
                        'product_discount_for_report' => $row['product_discount_for_report'],
                        'supplier_promo' => $row['supplier_promo'],
                        'rid' => $row['rid'],
                        'ppvz_spp_prc' => $row['ppvz_spp_prc'],
                        'ppvz_kvw_prc_base' => $row['ppvz_kvw_prc_base'],
                        'ppvz_kvw_prc' => $row['ppvz_kvw_prc'],
                        'ppvz_sales_commission' => $row['ppvz_sales_commission'],
                        'ppvz_for_pay' => $row['ppvz_for_pay'],
                        'ppvz_reward' => $row['ppvz_reward'],
                        'acquiring_fee' => $row['acquiring_fee'],
                        'acquiring_bank' => $row['acquiring_bank'],
                        'ppvz_vw' => $row['ppvz_vw'],
                        'ppvz_vw_nds' => $row['ppvz_vw_nds'],
                        'ppvz_office_id' => $row['ppvz_office_id'],
                        'ppvz_office_name' => isset($row['ppvz_office_name']) ? $row['ppvz_office_name'] : ' ',
                        'ppvz_supplier_id' => $row['ppvz_supplier_id'],
                        'ppvz_supplier_name' => $row['ppvz_supplier_name'],

                        'ppvz_inn' => $row['ppvz_inn'],
                        'declaration_number' => $row['declaration_number'],
                        'bonus_type_name' => isset($row['bonus_type_name']) ? $row['bonus_type_name'] : ' ',

                        'sticker_id' => $row['sticker_id'],
                        'site_country' => $row['site_country'],
                        'penalty' => $row['penalty'],
                        'additional_payment' => $row['additional_payment'],
                        'srid' => $row['srid'],
                    ]);
                }
            }
            $response = Http::withHeaders([
                'Authorization' => config('services.wb.statistic_key')
            ])->get('https://statistics-api.wildberries.ru/api/v1/supplier/reportDetailByPeriod', [
                'dateFrom' => $dateForm->format('Y-m-d'),
                'limit' => 10000, 'dateTo' => $dateForm->add(new DateInterval('P2D'))->format('Y-m-d'),
                'rrdid' => $row['rrd_id']
            ]);
            //dd($response->json());
            $rows = $response->json();
        } while ((isset($rows)));
        //dd($response->json());
    }
    protected function fetchOzon()
    {
        $response = Http::withHeaders([
            'Host' => 'api-seller.ozon.ru',
            'Client-Id' => config('services.ozon.client_id'),
            'Api-Key' => config('services.ozon.api_key'),
            'Content-Type' => 'application/json'
        ])->post('https://api-seller.ozon.ru/v3/product/info/stocks', [
            "filter" => [
                "visibility" => "ALL"
            ],
            "last_id" => "",
            "limit" => 100
        ]);
        $data = $response->json();
        $rows = $data['result']['items'];
        $last_id = $data['result']['last_id'];
        //dd($rows);
        if (empty($rows)) {
            return;
        }
        do {

            foreach ($rows as $row) {
                if (!DB::table('oz_info_stocks')->where('product_id', $row['product_id'])->exists()) {
                    $fbopresent = null;
                    $fboreserved = null;
                    $fbspersent = null;
                    $fbsreserved = null;
                    foreach ($row['stocks'] as $stocks) {
                        if ($stocks['type'] === 'fbo') {
                            $fbopresent = $stocks['present'];
                            $fboreserved = $stocks['reserved'];
                        } elseif ($stocks['type'] === 'fbs') {
                            $fbspresent = $stocks['present'];
                            $fbsreserved = $stocks['reserved'];
                        }
                    }
                    DB::table('oz_info_stocks')->insert([
                        'date' => (new DateTime())->format('Y-m-d'),
                        'product_id' => $row['product_id'],
                        'offer_id' => $row['offer_id'],
                        'fbo_present' => $fbopresent,
                        'fbo_reserved' => $fboreserved,
                        'fbs_present' => $fbspresent,
                        'fbs_reserved' => $fbsreserved,


                    ]);
                }

                // }
            }
            $response = Http::withHeaders([
                'Host' => 'api-seller.ozon.ru',
                'Client-Id' => config('services.ozon.client_id'),
                'Api-Key' => config('services.ozon.api_key'),
                'Content-Type' => 'application/json'
            ])->post('https://api-seller.ozon.ru/v3/product/info/stocks', [
                "filter" => [
                    "visibility" => "ALL"
                ],
                "last_id" => $last_id,
                "limit" => 100
            ]);
            $data = $response->json();
            $rows = $data['result']['items'];
            $last_id = $data['result']['last_id'];
        } while ((!empty($rows)));
        //dd($response->json());
    }
    protected function fetchOzonPosting(DateTime $dateForm)
    {
        $offset = 0;
        $response = Http::withHeaders([
            'Host' => 'api-seller.ozon.ru',
            'Client-Id' => config('services.ozon.client_id'),
            'Api-Key' => config('services.ozon.api_key'),
            'Content-Type' => 'application/json'
        ])->post('https://api-seller.ozon.ru/v3/posting/fbs/list', [
            "dir" => "ASC",
            "filter" => [
                "since" => $dateForm->sub(new DateInterval('P1D'))->format('c'),
                "status" =>  "",
                "to" => $dateForm->add(new DateInterval('P1D'))->format('c'),
            ],

            "limit" => 5,
            "offset" => 0,
            "translit" => true,
            "with" => [

                "analytics_data" => true,
                "financial_data" => true

            ]



        ]);
        $data = $response->json();
        $rows = $data['result']['postings'];
        if (empty($rows)) {
            return;
        }
        do {

            foreach ($rows as $row) {//'order_id', 'posting_number', 'sku'


                $id = null;
                $name = null;
                $warehouse_id = null;
                $warehouse = null;
                $tpl_provider_id = null;
                $tpl_provider = null;
                $cancel_reason_id = null;
                $cancel_reason = null;
                $cancellation_type = null;
                $cancelled_after_ship = null;
                $affect_cancellation_rating = null;
                $cancellation_initiator = null;
                $price = null;
                $offer_id = null;
                $name = null;
                $sku = null;
                $quantity = null;
                $mandatory_mark = null;
                $currency_code = null;
                $region = null;
                $city = null;
                $delivery_type = null;
                $is_premium = null;
                $payment_type_group_name = null;
                $warehouse_id = null;
                $warehouse = null;
                $tpl_provider_id = null;
                $tpl_provider = null;
                $delivery_date_begin = null;
                $delivery_date_end = null;
                $is_legal = null;
                $commission_amount = null;
                $commission_percent = null;
                $payout = null;
                $product_id = null;
                $old_price = null;
                $price = null;
                $total_discount_value = null;
                $total_discount_percent = null;
                $actions = null;
                $picking = null;
                $quantity = null;
                $client_price = null;

                $currency_code = null;

                $marketplace_service_item_fulfillment = null;
                $marketplace_service_item_pickup = null;
                $marketplace_service_item_dropoff_pvz = null;
                $marketplace_service_item_dropoff_sc = null;
                $marketplace_service_item_dropoff_ff = null;
                $marketplace_service_item_direct_flow_trans = null;
                $marketplace_service_item_return_flow_trans = null;
                $marketplace_service_item_deliv_to_customer = null;
                $marketplace_service_item_return_not_deliv_to_customer = null;
                $marketplace_service_item_return_part_goods_customer = null;
                $marketplace_service_item_return_after_deliv_to_customer = null;
                $cluster_from = null;
                $cluster_to = null;
                $products_requiring_gtd = null;
                $products_requiring_country = null;
                $products_requiring_mandatory_mark = null;
                $products_requiring_rnpt = null;
                $products_requiring_gtd = $row['requirements']['products_requiring_gtd'];
                $products_requiring_country = $row['requirements']['products_requiring_country'];
                $products_requiring_mandatory_mark = $row['requirements']['products_requiring_mandatory_mark'];
                $products_requiring_rnpt = $row['requirements']['products_requiring_rnpt'];
                $commission_amount = $row['financial_data']['products'][0]['commission_amount'];
                $commission_percent = $row['financial_data']['products'][0]['commission_percent'];
                $payout = $row['financial_data']['products'][0]['payout'];
                $product_id = $row['financial_data']['products'][0]['product_id'];
                $old_price = $row['financial_data']['products'][0]['old_price'];
                $price = $row['financial_data']['products'][0]['price'];
                $total_discount_value = $row['financial_data']['products'][0]['total_discount_value'];
                $total_discount_percent = $row['financial_data']['products'][0]['total_discount_percent'];
                //$actions = $row['financial_data']['products']['actions'];
                $picking = $row['financial_data']['products'][0]['picking'];
                $quantity = $row['financial_data']['products'][0]['quantity'];
                $client_price = $row['financial_data']['products'][0]['client_price'];
                $currency_code =  $row['financial_data']['products'][0]['currency_code'];

                $marketplace_service_item_fulfillment = $row['financial_data']['products'][0]['item_services']['marketplace_service_item_fulfillment'];
                $marketplace_service_item_pickup = $row['financial_data']['products'][0]['item_services']['marketplace_service_item_pickup'];
                $marketplace_service_item_dropoff_pvz = $row['financial_data']['products'][0]['item_services']['marketplace_service_item_dropoff_pvz'];
                $marketplace_service_item_dropoff_sc = $row['financial_data']['products'][0]['item_services']['marketplace_service_item_dropoff_sc'];
                $marketplace_service_item_dropoff_ff = $row['financial_data']['products'][0]['item_services']['marketplace_service_item_dropoff_ff'];
                $marketplace_service_item_direct_flow_trans = $row['financial_data']['products'][0]['item_services']['marketplace_service_item_direct_flow_trans'];
                $marketplace_service_item_return_flow_trans = $row['financial_data']['products'][0]['item_services']['marketplace_service_item_return_flow_trans'];
                $marketplace_service_item_deliv_to_customer = $row['financial_data']['products'][0]['item_services']['marketplace_service_item_deliv_to_customer'];
                $marketplace_service_item_return_not_deliv_to_customer = $row['financial_data']['products'][0]['item_services']['marketplace_service_item_return_not_deliv_to_customer'];
                $marketplace_service_item_return_part_goods_customer = $row['financial_data']['products'][0]['item_services']['marketplace_service_item_return_part_goods_customer'];
                $marketplace_service_item_return_after_deliv_to_customer = $row['financial_data']['products'][0]['item_services']['marketplace_service_item_return_after_deliv_to_customer'];
                $cluster_from = $row['financial_data']['cluster_from'];
                $cluster_to = $row['financial_data']['cluster_to'];
                $id = $row['delivery_method']['id'];
                $name = $row['delivery_method']['name'];
                $warehouse_id = $row['delivery_method']['warehouse_id'];
                $warehouse = $row['delivery_method']['warehouse'];
                $tpl_provider_id = $row['delivery_method']['tpl_provider_id'];
                $tpl_provider = $row['delivery_method']['tpl_provider'];


                $cancel_reason_id = $row['cancellation']['cancel_reason_id'];
                $cancel_reason = $row['cancellation']['cancel_reason'];
                $cancellation_type = $row['cancellation']['cancellation_type'];
                $cancelled_after_ship = $row['cancellation']['cancelled_after_ship'];
                $affect_cancellation_rating = $row['cancellation']['affect_cancellation_rating'];
                $cancellation_initiator = $row['cancellation']['cancellation_initiator'];
                $price = $row['products'][0]['price'];
                $offer_id = $row['products'][0]['offer_id'];
                $name = $row['products'][0]['name'];
                $sku = $row['products'][0]['sku'];
                $quantity = $row['products'][0]['quantity'];
                $mandatory_mark = $row['products'][0]['mandatory_mark'];
                $currency_code = $row['products'][0]['currency_code'];
                $region = $row['analytics_data']['region'];
                $city = $row['analytics_data']['city'];
                $delivery_type = $row['analytics_data']['delivery_type'];
                $is_premium = $row['analytics_data']['is_premium'];
                $payment_type_group_name = $row['analytics_data']['payment_type_group_name'];
                $warehouse_id = $row['analytics_data']['warehouse_id'];
                $warehouse = $row['analytics_data']['warehouse'];
                $tpl_provider_id = $row['analytics_data']['tpl_provider_id'];
                $tpl_provider = $row['analytics_data']['tpl_provider'];
                $delivery_date_begin = $row['analytics_data']['delivery_date_begin'];
                $delivery_date_end = $row['analytics_data']['delivery_date_end'];
                $is_legal = $row['analytics_data']['is_legal'];


                if (!DB::table('oz_posting_fbs')-> where([
                    'order_id' => $row['order_id'], 'posting_number' => $row['posting_number']
                    , 'sku' => $sku])->exists()) {
                DB::table('oz_posting_fbs')->insert([
                    "posting_number" => $row['posting_number'],
                    "order_id" => $row['order_id'],
                    "order_number" => $row['order_number'],
                    "status" => $row['status'],
                    "tracking_number" => $row['tracking_number'],
                    "tpl_integration_type" => $row['tpl_integration_type'],
                    "in_process_at" => (new DateTime($row['in_process_at']))->format('Y-m-d'),
                    "shipment_date" => (new DateTime($row['shipment_date']))->format('Y-m-d'),
                    "delivering_date" => (new DateTime($row['delivering_date']))->format('Y-m-d'),
                    "customer" => $row['customer'],
                    "addressee" => $row['addressee'],
                    "barcodes" => $row['barcodes'],
                    "is_express" => $row['is_express'],
                    "parent_posting_number" => $row['parent_posting_number'],
                    "available_actions" => json_encode($row['available_actions']),
                    "multi_box_qty" => $row['multi_box_qty'],
                    "is_multibox" => $row['is_multibox'],
                    //"substatus" => $row['substatus'],
                    'products_requiring_gtd' => json_encode($products_requiring_gtd),
                    'products_requiring_country' => json_encode($products_requiring_country),
                    'products_requiring_mandatory_mark' => json_encode($products_requiring_mandatory_mark),
                    'products_requiring_rnpt' => json_encode($products_requiring_rnpt),
                    'commission_amount' => $commission_amount,
                    'commission_percent' => $commission_percent,
                    'payout' => $payout,
                    'product_id' => $product_id,
                    'old_price' => $old_price,
                    'price' => $price,
                    'total_discount_value' => $total_discount_value,
                    'total_discount_percent' => $total_discount_percent,
                    'actions' => json_encode(null),
                    'picking' => json_encode($picking),
                    'quantity' => $quantity,
                    'client_price' => $client_price,
                    'currency_code' =>  $currency_code,

                    'marketplace_service_item_fulfillment' => $marketplace_service_item_fulfillment,
                    'marketplace_service_item_pickup' => $marketplace_service_item_pickup,
                    'marketplace_service_item_dropoff_pvz' => $marketplace_service_item_dropoff_pvz,
                    'marketplace_service_item_dropoff_sc' => $marketplace_service_item_dropoff_sc,
                    'marketplace_service_item_dropoff_ff' => $marketplace_service_item_dropoff_ff,
                    'marketplace_service_item_direct_flow_trans' => $marketplace_service_item_direct_flow_trans,
                    'marketplace_service_item_return_flow_trans' => $marketplace_service_item_return_flow_trans,
                    'marketplace_service_item_deliv_to_customer' => $marketplace_service_item_deliv_to_customer,
                    'marketplace_service_item_return_not_deliv_to_customer' => $marketplace_service_item_return_not_deliv_to_customer,
                    'marketplace_service_item_return_part_goods_customer' => $marketplace_service_item_return_part_goods_customer,
                    'marketplace_service_item_return_after_deliv_to_customer' => $marketplace_service_item_return_after_deliv_to_customer,
                    'cluster_from' => $cluster_from,
                    'cluster_to' => $cluster_to,
                    'delivery_method_id' => $id,
                    'name' => $name,
                    'warehouse_id' => $warehouse_id,
                    'warehouse' => $warehouse,
                    'tpl_provider_id' => $tpl_provider_id,
                    'tpl_provider' => $tpl_provider,


                    'cancel_reason_id' => $cancel_reason_id,
                    'cancel_reason' => $cancel_reason,
                    'cancellation_type' => $cancellation_type,
                    'cancelled_after_ship' => $cancelled_after_ship,
                    'affect_cancellation_rating' => $affect_cancellation_rating,
                    'cancellation_initiator' => $cancellation_initiator,
                    'price' => $price,
                    'offer_id' => $offer_id,
                    'name' => $name,
                    'sku' => $sku,
                    'quantity' => $quantity,
                    'mandatory_mark' => json_encode($mandatory_mark),
                    'currency_code' => $currency_code,
                    'region' => $region,
                    'city' => $city,
                    'delivery_type' => $delivery_type,
                    'is_premium' => $is_premium,
                    'payment_type_group_name' => $payment_type_group_name,
                    'warehouse_id' => $warehouse_id,
                    'warehouse' => $warehouse,
                    'tpl_provider_id' => $tpl_provider_id,
                    'tpl_provider' => $tpl_provider,
                    'delivery_date_begin' => (new DateTime($delivery_date_begin))->format('Y-m-d'),
                    'delivery_date_end' => (new DateTime($delivery_date_end))->format('Y-m-d'),
                    'is_legal' => $is_legal,
                ]);
                }
            }
            $offset+=5;
            $response = Http::withHeaders([
                'Host' => 'api-seller.ozon.ru',
                'Client-Id' => config('services.ozon.client_id'),
                'Api-Key' => config('services.ozon.api_key'),
                'Content-Type' => 'application/json'
            ])->post('https://api-seller.ozon.ru/v3/posting/fbs/list', [
                "dir" => "ASC",
                "filter" => [
                    "since" => $dateForm->sub(new DateInterval('P1D'))->format('c'),
                    "status" =>  "",
                    "to" => $dateForm->add(new DateInterval('P1D'))->format('c'),
                ],

                "limit" => 5,
                "offset" => $offset,
                "translit" => true,
                "with" => [

                    "analytics_data" => true,
                    "financial_data" => true

                ]
            ]);
            $data = $response->json();
            $rows = $data['result']['postings'];
        } while ((!empty($rows)));
    }
    protected function fetchOzonPostingFbo(DateTime $dateForm)
    {
        $offset = 0;
        $response = Http::withHeaders([
            'Host' => 'api-seller.ozon.ru',
            'Client-Id' => config('services.ozon.client_id'),
            'Api-Key' => config('services.ozon.api_key'),
            'Content-Type' => 'application/json'
        ])->post('https://api-seller.ozon.ru/v2/posting/fbo/list', [
            "dir" => "ASC",
            "filter" => [
                "since" => $dateForm->sub(new DateInterval('P1D'))->format('c'),
                "status" =>  "",
                "to" => $dateForm->add(new DateInterval('P1D'))->format('c'),
            ],

            "limit" => 5,
            "offset" => 0,
            "translit" => true,
            "with" => [

                "analytics_data" => true,
                "financial_data" => true

            ]



        ]);
        $data = $response->json();
        dd($data);
    }
}
