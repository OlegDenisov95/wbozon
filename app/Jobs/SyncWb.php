<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use DateInterval;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class SyncWb implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 2 * 60;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $date = new DateTime('-4 day');
        $this->fetchPrices();
        $this->fetchStocks($date);
        $this->fetchIncomes($date);
        $this->fetchOrders($date);
        $this->fetchSales($date);
        $this->fetchSalesReports($date);
    }
    protected function fetchPrices()
    {
        $date = (new DateTime())->format('Y-m-d H:i:s');
        DB::table('wb_prices')->where('date', $date)->delete();
        $response = Http::withHeaders([
            'Authorization' => config('services.wb.standard_key')
        ])->get('https://suppliers-api.wildberries.ru/public/api/v1/info');
        foreach ($response->json() as $row) {
            // if (!DB::table('wb_prices')->where('nm_id', $row['nmId'])->exists()) {


            DB::table('wb_prices')->insert([
                'nm_id' => $row['nmId'],
                'date' => $date,
                'price' => $row['price'],
                'discount' => $row['discount'],
                'promo_code' => $row['promoCode'],
            ]);
            // }

            // DB::table('wb_prices')->upsert([
            //     'nm_id' => $row['nmId'],
            //     'date' => (new DateTime())->format('Y-m-d'),
            //     'price' => $row['price'],
            //     'discount' => $row['discount'],
            //     'promo_code' => $row['promoCode'],
            // ], [
            //     'nm_id'
            // ]);
        }
    }
    protected function fetchStocks(DateTime $dateForm)
    {
        $date = (new DateTime())->format('Y-m-d');
        $response = Http::withHeaders([
            'Authorization' => config('services.wb.statistic_key')
        ])->get('https://statistics-api.wildberries.ru/api/v1/supplier/stocks', ['dateFrom' => $dateForm->format('Y-m-d')]);
        DB::table('wb_stocks')->where('date', $date)->delete();
        foreach ($response->json() as $row) {
            // if (!DB::table('wb_stocks')->where([
            //     'barcode' => $row['barcode'], 'warehouse_name' => $row['warehouseName']
            // ])->exists())

            DB::table('wb_stocks')->insert([
                'date' => $date,
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

            // if (!DB::table('wb_incomes')->where([
            //     'income_id' => $row['incomeId'],
            //     'barcode' => $row['barcode']
            // ])->exists()) {
            DB::table('wb_incomes')->upsert(
                [
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
                ],
                ['income_id', 'barcode']
            );
            // }
        }
    }


    protected function fetchOrders(DateTime $dateForm)
    {
        $response = Http::withHeaders([
            'Authorization' => config('services.wb.statistic_key')
        ])->get('https://statistics-api.wildberries.ru/api/v1/supplier/orders', ['dateFrom' => $dateForm->format('Y-m-d'), 'flag' => 1]);
        //dd($response->json());
        foreach ($response->json() as $row) {
            // if (!DB::table('wb_orders')->where('odid', $row['odid'])->exists()) {
            DB::table('wb_orders')->upsert([
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
            ], ['odid']);
            // }
        }
    }
    protected function fetchSales(DateTime $dateForm)
    {
        $response = Http::withHeaders([
            'Authorization' => config('services.wb.statistic_key')
        ])->get('https://statistics-api.wildberries.ru/api/v1/supplier/sales', ['dateFrom' => $dateForm->format('Y-m-d')]);
        //dd($response->json());
        foreach ($response->json() as $row) {
            // if (!DB::table('wb_sales')->where('sale_id', $row['saleID'])->exists()) {
            DB::table('wb_sales')->upsert([
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
            ], ['sale_id']);
            // }
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
                // if (!DB::table('wb_sales_reports')->where('rrd_id', $row['rrd_id'])->exists()) {
                // dd($row['rid']);
                DB::table('wb_sales_reports')->upsert([
                    'realizationreport_id' => $row['realizationreport_id'],
                    'date_from' => (new DateTime($row['date_from']))->format('Y-m-d'),
                    'date_to' => (new DateTime($row['date_to']))->format('Y-m-d'),
                    'create_dt' => (new DateTime($row['create_dt']))->format('Y-m-d H:i:s'),
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
                ], ['rrd_id']);
                // }
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
}
