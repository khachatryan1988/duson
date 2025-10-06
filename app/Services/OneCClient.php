<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class OneCClient
{
    private Client $http;

    public function __construct()
    {
        $this->http = new Client([
            'base_uri'       => rtrim(config('onec.base_uri'), '/'),
            'timeout'        => 2.0,
            'connect_timeout'=> 2.0,
            'headers'        => [
                'Authorization' => 'Basic ' . base64_encode(
                        config('onec.auth_user') . ':' . config('onec.auth_pass')
                    ),
            ],
        ]);
    }

    /**
     * @return array{ok:bool, quantity?:int, price?:int|string, error?:string}
     */
    public function getItemPriceAndQuantity(string $itemId): array
    {
        try {
            $res = $this->http->get('/Promas/hs/eshopitems/GET_ITEMS_PRICE/', [
                'query' => ['ItemID' => $itemId],
            ]);
            $data = json_decode($res->getBody(), true);

            if (!is_array($data) || empty($data['Items'][0])) {
                return ['ok' => false, 'error' => 'unexpected_response'];
            }

            $p = $data['Items'][0];

            $qty = isset($p['Quantity']) ? (int)$p['Quantity'] : 0;

            $price = null;
            if (isset($p['Price'])) {
                $price = preg_replace('/\s+/u', '', (string)$p['Price']);
                if (is_numeric($price)) $price = (int)$price;
            }

            return ['ok' => true, 'quantity' => $qty, 'price' => $price];
        } catch (GuzzleException $e) {
            return ['ok' => false, 'error' => 'http_error'];
        }
    }
}
