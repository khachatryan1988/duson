<?php

namespace App\Library;

use App\Models\Order;
use App\Models\Product;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use App\Traits\CartTrait;


class OnlineHdm
{
    use CartTrait;

    private int $orderID;

    public function __construct(int $orderID)
    {
        $this->orderID = $orderID;
    }

    private function getToken(): string
    {
        $user = ["username" => "user_promasllc", "password" => "7C2A83EAD1"];
        $url = "https://store.payx.am/api/Login/LoginUser/";

        $response = $this->makeApiRequest('POST', $url, json_encode($user));

        $hdmToken = '';
        if ($response && $response->getStatusCode() === 200) {
            $hdmToken = $response->getHeader('token')[0];
            file_put_contents(storage_path('logs/hdm.log'), 'Token: ' . $this->orderID . '  ' . now()->format('d.m.Y H:i:s') . "\n", FILE_APPEND);
        }
        return $hdmToken;
    }


    private function getOrderData(): string
    {
        $uniqueCode = substr(md5(now()), 2);
//        $order = Order::find($this->orderID);

//        $OnlineHdmItems = new OnlineHdm($this->orderID);
        $order = Order::find($this->orderID);
        $items = $order->items;
//        $orderData = [
//            "uniqueCode" => $uniqueCode,
//            "cashAmount" => 0,
//            "cardAmount" => $order->total,
//            "partnerTin" => "0",
//            "partialAmount" => 0,
//            "prePaymentAmount" => 0,
//            "additionalDiscount" => 0,
//            "additionalDiscountType" => 0,
//            "products" => []
//        ];
        $orderData = [
            "uniqueCode" => $uniqueCode,
            "cashAmount" => 0,
            "cardAmount" => $order->total,
            "partnerTin" => "0",
            "partialAmount" => 0,
            "prePaymentAmount" => 0,
            "additionalDiscount" => 0,
            "additionalDiscountType" => 0,
            "products" => []
        ];

        foreach ($items as $item) {
            $productId = $item->id;
            $product = Product::find($productId);
//            $productId = $item->getId();
//            $product = Product::where('id', $productId)->first();
//            $productAdg = $product->adgt;
//            $productNumber = $product->item_id;
//            $productName = $product->name_arm;
//            $productUnit = $product->unit;
//            $productPrice = $product->price;


//            $productData = [
//                "id" => 0,
//                "adgCode" => $productAdg,
//                "goodCode" => $productNumber,
//                "goodName" => $productName,
//                "quantity" => $item->getQuantity(),
//                "price" => $productPrice,
//                "unit" => $productUnit,
//                "dep" => 1,
//                "discount" => 0,
//                "discountType" => 0,
//                "receiptProductId" => 0
//            ];
            $productData = [
                "id" => 0,
                "adgCode" => $product->adgt,
                "goodCode" => $product->item_id,
                "goodName" => $product->name_arm,
                "quantity" => $item->pivot->quantity,
                "price" => $item->pivot->price,
                "unit" => $product->unit,
                "dep" => 1,
                "discount" => 0,
                "discountType" => 0,
                "receiptProductId" => 0
            ];
            $orderData["products"][] = $productData;
        }


        if (!empty($order->shipping_cost)) {
            $orderData["products"][] = [
                "id" => 0,
                "adgCode" => "46.73",
                "goodCode" => "00-00013304",
                "goodName" => "Առաքման ծառայություն",
                "quantity" => 1,
                "price" => $order->shipping_cost,
                "unit" => "հատ",
                "dep" => 1,
                "discount" => 0,
                "discountType" => 0,
                "receiptProductId" => 0
            ];

        }
        return json_encode($orderData);
    }

    private function makeApiRequest($method, $url, $data, $token = null)
    {
        $headers = ['Content-Type' => 'application/json'];
        if ($token) {
            $headers['Authorization'] = 'Bearer ' . $token;
        }

        $client = new Client();
        $response = $client->request($method, $url, [
            'body' => $data,
            'timeout' => 30,
            'headers' => $headers,
        ]);

        return $response;
    }

    public function createHdm(bool $sendEmails = true): void
    {
        $token = $this->getToken();
        $data  = $this->getOrderData();

        $urlPrint = 'https://store.payx.am/api/Hdm/Print';
        $response = $this->makeApiRequest('POST', $urlPrint, $data, $token);

        if ($response && $response->getStatusCode() === 200) {
            $body = json_decode($response->getBody()->getContents(), true);
            $link = data_get($body, 'link');
            $receiptId = data_get($body, 'res.receiptId');

            File::append(
                storage_path('logs/hdm.log'),
                'Response: '.print_r($body, true).'  '.now()->format('d.m.Y H:i:s')."\n"
            );

            $order = Order::find($this->orderID);
            if ($order) {
                $order->el_hdm   = $receiptId;
                $order->hdm_link = $link;
                $order->save();
            }

            if ($sendEmails && $receiptId && $order) {
                $urlEmail = 'https://store.payx.am/api/Hdm/SendEmail';

                $emails = array_values(array_filter([
                    'domusonline.web@gmail.com',
                    $order->email,
                ]));

                foreach ($emails as $to) {
                    $resp = Http::withHeaders([
                        'Authorization' => 'Bearer '.$token,
                        'Content-Type'  => 'application/json',
                    ])
                        ->timeout(30)
                        ->post($urlEmail, [
                            'historyId' => 0,
                            'receiptId' => (int) $receiptId,
                            'email'     => $to,
                            'language'  => 0,
                        ]);

                    if ($resp->successful()) {
                        File::append(
                            storage_path('logs/hdm.log'),
                            'Email OK: '.print_r($resp->json(), true).' '.now()->format('d.m.Y H:i:s')."\n"
                        );
                    } elseif ($resp->status() === 400) {
                        $error_message = $resp['message'] ?? 'Unknown error occurred';
                        File::append(
                            storage_path('logs/hdm.log'),
                            'Email 400: '.print_r($error_message, true).' '.now()->format('d.m.Y H:i:s')."\n"
                        );
                    } else {
                        File::append(
                            storage_path('logs/hdm.log'),
                            'Email FAIL: '.$resp->status().' '.$resp->body().' '.now()->format('d.m.Y H:i:s')."\n"
                        );
                    }
                }
            }
        } else {
            $error_message = is_object($response) ? $response->getBody() : 'empty response';
            File::append(
                storage_path('logs/hdm.log'),
                'Something went wrong: '.print_r($error_message, true).'  '.now()->format('d.m.Y H:i:s')."\n"
            );
        }
    }


    public function reverseHdm(): void
    {
        $order = Order::find($this->orderID);
        $receiptId = $order->el_hdm;
        if ((int)$receiptId) {
            $token = $this->getToken();
            $url = 'https://store.payx.am/api/Hdm/ReverseByReceiptId?receiptId=' . (int)$receiptId;

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/json',
            ])->timeout(30)->post($url);

            if ($response->getStatusCode() === 200) {
                $responseData = $response->json();
                $logMessage = 'Reverse: ' . print_r($responseData, true) . '  ' . now()->format('d.m.Y H:i:s') . "\n";
                File::append(storage_path('logs/hdm.log'), $logMessage);
            }
        }
    }

}
