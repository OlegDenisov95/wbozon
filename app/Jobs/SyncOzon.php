<?php

namespace App\Jobs;

use App\Models\OzInfoStock;
use App\Models\OzPostingFbs;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use DateInterval;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class SyncOzon implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 2 * 60;

    protected DateTime $from;
    protected DateTime $to;

    /**
     * Create a new job instance.
     */
    public function __construct(DateTime $from, DateTime $to)
    {
        $this->from = $from;
        $this->to = $to;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
       $this->fetchOzon();
         $this->fetchOzonPosting();
      //   $this->fetchOzonPostingFbo();
    }
    protected function fetchOzon()
    {
        $today = (new DateTime())->format('Y-m-d');
        $response = Http::retry(3, 100)->withHeaders([
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
        DB::table('oz_info_stocks')->where('date', $today)->delete();
        //dd($rows);
        if (empty($rows)) {
            return;
        }
        do {

            foreach ($rows as $row) {
                // if (!DB::table('oz_info_stocks')->where('product_id', $row['product_id'])->exists()) {
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
                OzInfoStock::create([
                    'date' => $today,
                    'product_id' => $row['product_id'],
                    'offer_id' => $row['offer_id'],
                    'fbo_present' => $fbopresent,
                    'fbo_reserved' => $fboreserved,
                    'fbs_present' => $fbspresent,
                    'fbs_reserved' => $fbsreserved,


                ]);
                // }


            }
            $response = Http::retry(3, 100)->withHeaders([
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
    protected function fetchOzonPosting()
    {
        $offset = 0;
        $response = Http::retry(3, 100)->withHeaders([
            'Host' => 'api-seller.ozon.ru',
            'Client-Id' => config('services.ozon.client_id'),
            'Api-Key' => config('services.ozon.api_key'),
            'Content-Type' => 'application/json'
        ])->post('https://api-seller.ozon.ru/v3/posting/fbs/list', [
            "dir" => "ASC",
            "filter" => [
                "since" => $this->from->format('c'),
                "status" =>  "",
                "to" => $this->to->format('c'),
            ],

            "limit" => 100,
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
            $dataForSave = [];
            foreach ($rows as $row) {
                for ($i = 0; $i < count($row['products']); $i++) {

                    $dataForSave[] = [
                        "posting_number" => $row['posting_number'],
                        "order_id" => $row['order_id'],
                        "order_number" => $row['order_number'],
                        "status" => $row['status'],
                        "tracking_number" => $row['tracking_number'],
                        "tpl_integration_type" => $row['tpl_integration_type'],
                        "in_process_at" => (new DateTime($row['in_process_at']))->format('Y-m-d H:i:s'),
                        "shipment_date" => (new DateTime($row['shipment_date']))->format('Y-m-d H:i:s'),
                        "delivering_date" => (new DateTime($row['delivering_date']))->format('Y-m-d H:i:s'),
                        "customer" => $row['customer'],
                        "addressee" => $row['addressee'],
                        "barcodes" => $row['barcodes'],
                        "is_express" => $row['is_express'],
                        "parent_posting_number" => $row['parent_posting_number'],
                        "available_actions" => json_encode($row['available_actions']),
                        "multi_box_qty" => $row['multi_box_qty'],
                        "is_multibox" => $row['is_multibox'],
                        'products_requiring_gtd' => json_encode($row['requirements']['products_requiring_gtd']),
                        'products_requiring_country' => json_encode($row['requirements']['products_requiring_country']),
                        'products_requiring_mandatory_mark' => json_encode($row['requirements']['products_requiring_mandatory_mark']),
                        'products_requiring_rnpt' => json_encode($row['requirements']['products_requiring_rnpt']),
                        'commission_amount' => $row['financial_data']['products'][$i]['commission_amount'],
                        'commission_percent' =>  $row['financial_data']['products'][$i]['commission_percent'],
                        'payout' => $row['financial_data']['products'][$i]['payout'],
                        'product_id' => $row['financial_data']['products'][$i]['product_id'],
                        'old_price' => $row['financial_data']['products'][$i]['old_price'],
                        'price' => $row['financial_data']['products'][$i]['price'],
                        'total_discount_value' => $row['financial_data']['products'][$i]['total_discount_value'],
                        'total_discount_percent' =>  $row['financial_data']['products'][$i]['total_discount_percent'],
                        'actions' => json_encode(null),
                        'picking' => json_encode($row['financial_data']['products'][$i]['picking']),
                        'quantity' => $row['financial_data']['products'][$i]['quantity'],
                        'client_price' => $row['financial_data']['products'][$i]['client_price'],
                        'currency_code' =>  $row['financial_data']['products'][$i]['currency_code'],

                        'marketplace_service_item_fulfillment' => $row['financial_data']['products'][$i]['item_services']['marketplace_service_item_fulfillment'],
                        'marketplace_service_item_pickup' => $row['financial_data']['products'][$i]['item_services']['marketplace_service_item_pickup'],
                        'marketplace_service_item_dropoff_pvz' => $row['financial_data']['products'][$i]['item_services']['marketplace_service_item_dropoff_pvz'],
                        'marketplace_service_item_dropoff_sc' => $row['financial_data']['products'][$i]['item_services']['marketplace_service_item_dropoff_sc'],
                        'marketplace_service_item_dropoff_ff' => $row['financial_data']['products'][$i]['item_services']['marketplace_service_item_dropoff_ff'],
                        'marketplace_service_item_direct_flow_trans' => $row['financial_data']['products'][$i]['item_services']['marketplace_service_item_direct_flow_trans'],
                        'marketplace_service_item_return_flow_trans' =>  $row['financial_data']['products'][$i]['item_services']['marketplace_service_item_return_flow_trans'],
                        'marketplace_service_item_deliv_to_customer' => $row['financial_data']['products'][$i]['item_services']['marketplace_service_item_deliv_to_customer'],
                        'marketplace_service_item_return_not_deliv_to_customer' =>  $row['financial_data']['products'][$i]['item_services']['marketplace_service_item_return_not_deliv_to_customer'],
                        'marketplace_service_item_return_part_goods_customer' => $row['financial_data']['products'][$i]['item_services']['marketplace_service_item_return_part_goods_customer'],
                        'marketplace_service_item_return_after_deliv_to_customer' => $row['financial_data']['products'][$i]['item_services']['marketplace_service_item_return_after_deliv_to_customer'],
                        'cluster_from' => $row['financial_data']['cluster_from'],
                        'cluster_to' =>  $row['financial_data']['cluster_to'],
                        'delivery_method_id' => $row['delivery_method']['id'],
                        'delivery_method_name' => $row['delivery_method']['name'],
                        'warehouse_id' => $row['delivery_method']['warehouse_id'],
                        'warehouse' => $row['delivery_method']['warehouse'],
                        'tpl_provider_id' => $row['delivery_method']['tpl_provider_id'],
                        'tpl_provider' => $row['delivery_method']['tpl_provider'],


                        'cancel_reason_id' => $row['cancellation']['cancel_reason_id'],
                        'cancel_reason' => $row['cancellation']['cancel_reason'],
                        'cancellation_type' => $row['cancellation']['cancellation_type'],
                        'cancelled_after_ship' => $row['cancellation']['cancelled_after_ship'],
                        'affect_cancellation_rating' => $row['cancellation']['affect_cancellation_rating'],
                        'cancellation_initiator' => $row['cancellation']['cancellation_initiator'],
                        // 'price' => $row['products'][$i]['price'],
                        'offer_id' => $row['products'][$i]['offer_id'],
                        'name' => $row['products'][$i]['name'],
                        'sku' => $row['products'][$i]['sku'],
                        // 'quantity' => $row['products'][$i]['quantity'],
                        'mandatory_mark' => json_encode($row['products'][$i]['mandatory_mark']),
                        // 'currency_code' => $row['products'][$i]['currency_code'],
                        'region' => $row['analytics_data']['region'],
                        'city' =>  $row['analytics_data']['city'],
                        'delivery_type' => $row['analytics_data']['delivery_type'],
                        'is_premium' => $row['analytics_data']['is_premium'],
                        'payment_type_group_name' => $row['analytics_data']['payment_type_group_name'],
                        'warehouse_id' => $row['analytics_data']['warehouse_id'],
                        'warehouse' => $row['analytics_data']['warehouse'],
                        'tpl_provider_id' => $row['analytics_data']['tpl_provider_id'],
                        'tpl_provider' => $row['analytics_data']['tpl_provider'],
                        'delivery_date_begin' => (new DateTime($row['analytics_data']['delivery_date_begin']))->format('Y-m-d H:i:s'),
                        'delivery_date_end' => (new DateTime($row['analytics_data']['delivery_date_end']))->format('Y-m-d H:i:s'),
                        'is_legal' => $row['analytics_data']['is_legal'],


                    ];
                }
            }
            $dataForSaveChunks = array_chunk($dataForSave, 1000);
            foreach ($dataForSaveChunks as $chunk) {
                OzPostingFbs::upsert($chunk, ['order_id', 'posting_number', 'sku']);
            }
            $offset += 100;
            $response = Http::retry(3, 100)->withHeaders([
                'Host' => 'api-seller.ozon.ru',
                'Client-Id' => config('services.ozon.client_id'),
                'Api-Key' => config('services.ozon.api_key'),
                'Content-Type' => 'application/json'
            ])->post('https://api-seller.ozon.ru/v3/posting/fbs/list', [
                "dir" => "ASC",
                "filter" => [
                    "since" => $this->from->format('c'),
                    "status" =>  "",
                    "to" => $this->to->format('c'),
                ],

                "limit" => 100,
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
    protected function fetchOzonPostingFbo()
    {
        $offset = 0;
        $response = Http::retry(3, 100)->withHeaders([
            'Host' => 'api-seller.ozon.ru',
            'Client-Id' => config('services.ozon.client_id'),
            'Api-Key' => config('services.ozon.api_key'),
            'Content-Type' => 'application/json'
        ])->post('https://api-seller.ozon.ru/v2/posting/fbo/list', [


                "dir"=> "ASC",
                "filter"=>

            [

                "since"=> "2021-09-01T00:00:00.000Z",
                "status"=> "",
                "to"=> "2022-11-17T10:44:12.828Z"

            ],
            "limit"=> 5,
            "offset"=> 0,
            "translit"=> true,
            "with"=>

                [
                    "analytics_data"=> true,
                    "financial_data"=> true
                ]


            // "dir" => "ASC",
            // "filter" => [
            //     "since" => $this->from->format("Y-m-d\TH:i:s\Z"),
            //     "status" =>  "",
            //     "to" => (new DateTime('now'))->format("Y-m-d\TH:i:s\Z"),
            // ],

            // "limit" => 100,
            // "offset" => 0,
            // "translit" => true,
            // "with" => [

            //     "analytics_data" => true,
            //     "financial_data" => true





        ]);
        $data = $response->json();
        dd($data);
        $rows = $data['result']['postings'];

    }

    // protected function fetchOzonPostingFbo(DateTime $dateForm)
    // {
    //     $offset = 0;
    //     $response =  Http::retry(3, 100)->withHeaders([
    //         'Host' => 'api-seller.ozon.ru',
    //         'Client-Id' => config('services.ozon.client_id'),
    //         'Api-Key' => config('services.ozon.api_key'),
    //         'Content-Type' => 'application/json'
    //     ])->post(
    //         'https://api-seller.ozon.ru/v2/posting/fbo/list',
    //         [
    //             "dir" => "ASC",
    //             "filter" => [
    //                 "since" => "2023-01-01T00:00:00Z",
    //                 "to" => "2023-04-06T00:00:00Z",
    //                 "status" => ""
    //             ],
    //             "limit" => 100,
    //             "offset" => $offset,
    //             "translit" => true,
    //             "with" => [
    //                 "analytics_data" => true,
    //                 "financial_data" => true
    //             ]
    //         ]
    //     );
    //     $data = $response->json();
    //     dd($data);
    // }
}
