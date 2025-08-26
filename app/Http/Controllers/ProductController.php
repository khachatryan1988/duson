<?php

namespace App\Http\Controllers;

use App\Models\Product;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    public function search(Request $request)
    {
        $query = $request->input('query');

        $products = Product::where(function ($queryBuilder) use ($query) {
            $queryBuilder->whereRaw('LOWER(title) LIKE LOWER(?)', ["%{$query}%"])
                ->orWhereRaw('LOWER(item_id) LIKE LOWER(?)', ["%{$query}%"])
                ->orWhereRaw('LOWER(slug) LIKE LOWER(?)', ["%{$query}%"])
                ->orWhereRaw('LOWER(size) LIKE LOWER(?)', ["%{$query}%"])
//            ->orWhereRaw('LOWER(description) LIKE LOWER(?)', ["%{$query}%"])
                ->orWhereHas('category', function ($q) use ($query) {
                    $q->where('title', 'LIKE', "%{$query}%");
                });
        })
            ->paginate(100);

        return view('search.results', compact('products'));
    }



    public function show(Request $request, Product $product)
    {


        if (!$request->session()->has('lastViews')) {
            $request->session()->put('lastViews', []);
        }
        $lastViews = $request->session()->get('lastViews');

        if (!in_array($product->id, $lastViews)) {
            $request->session()->push('lastViews', $product->id);
        }

        if (!empty($lastViews)) {

            $lastViewedProducts = Product::whereIn('id', $lastViews)->where('id', '!=', $product->id)->take(8)->get();

        } else {
            $lastViewedProducts = null;
        }

        try {
            $client = new Client();
            $ic_api = 'http://178.160.203.146:1728';
            //http://62.89.21.215:11556
            //http://178.160.203.146:1728 //live
            //http://192.168.150.159 //local
            $response = $client->request('GET',
                $ic_api . "/Promas/hs/eshopitems/GET_ITEMS_PRICE/?ItemID=" . $product->item_id,
                [
                    'timeout' => 2, // Response timeout
                    'connect_timeout' => 2, // Connection timeout
                    'blocking' => true,
                    'headers' => array(
                        'Authorization' => 'Basic ' . base64_encode('Eshop:5tfEKwP9')
                    )
                ]
            );
        } catch (\Exception $ex) {

        }


        if (!empty($response)) {
            $data = json_decode($response->getBody(), true);
            if (is_array($data) && isset($data["Items"]) && isset($data["Items"][0])) {
                $product_data = $data["Items"][0];
                if (isset($product_data["Quantity"])) {
                    $quantity = intval($product_data["Quantity"]);
                }
                if (isset($product_data["Price"])) {
                    $price = preg_replace('/\s+/u', '', $product_data["Price"]);
                }
            }

            $product = Product::find($product->id);

            $avgPrice = $product->avg_price;

            $oldPrice = $product->old_price;

            if (empty($avgPrice) && empty($oldPrice)) {
                $product->quantity = $quantity;
                $product->price = $price;
                $product->save();
            }


        }

        return view("product")->with([
            'product' => $product,
            'products' => $product->relatedProducts(),
            'lastViews' => $lastViewedProducts
        ]);
    }


}

