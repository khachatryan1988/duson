<?php

namespace App\Library;

use App\Http\Requests\CheckoutRequest;
use App\Models\Address;
use App\Models\City;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\Product;
use App\Models\State;
use App\Traits\CartTrait;
use Illuminate\Http\Request;
use Cart;
use Auth;
use Webs\Transaction\Models\Transaction;
use GuzzleHttp\Client;

class Product1C
{
    use CartTrait;

    public $statuses = [];

    public function __construct()
    {
        $this->statuses = OrderStatus::pluck('id', 'key');

    }


    public function show(Request $request)
    {
        $states = State::all();
        $cities = City::whereNotNull('state_id')->get();
        $totals = $this->getCartDetails();

        if (!empty(old())) {
            $form = old();
        } else if (!empty(session('checkout'))) {
            $form = session('checkout');
        } else {
            $form = [];
        }

        return view('checkout')
            ->with($totals)
            ->with([
                'states' => $states,
                'cities' => $cities,
                'form' => $form
            ]);
    }


    public function getTotals(Request $request)
    {

        $totals = [
            'shipping' => number_format($this->calculateShippingCost($request->city, $request->state), 0, 2) . ' ' . trans('դր․'),
            'subtotal' => number_format($this->cart()->getItemsSubtotal(), 0, 2) . ' ' . trans('դր․'),
            'total' => number_format($this->cart()->getTotal(), 0, 2) . ' ' . trans('դր․'),
        ];
        return $totals;
    }

    public function confirmCheckout(CheckoutRequest $request)
    {

        session()->put('checkout', $request->all());
        $city = City::find($request->city);

        $address = Address::create([
            'street' => $request->street,
            'home' => $request->home,
            'city' => $request->city,
            'state' => $request->state,
            'distance' => !empty($city) ? $city->km : 0,
        ]);

        $order = Order::create([
            'user_id' => Auth::check() ? Auth::user()->id : null,
            'invoice_no' => strtotime('now'),
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'phone1' => $request->phone1,
            'sub_total' => $this->cart()->getItemsSubtotal(),
            'shipping_cost' => $this->cart()->sumActionsAmount(),
            'total' => $this->cart()->getTotal(),
            'notes' => $request->notes,
            'el_hdm' => $request->el_hdm,
        ]);

        $order->statuses()->attach($this->statuses['pending']);


        $items = $this->getItems();

        foreach ($items as $item) {
            $order->items()->attach($item->getId(), [
                'quantity' => $item->getQuantity(),
                'price' => $item->getPrice(),
                'total' => $item->getDetails()['total_price'],
            ]);
        }


        $order->update([
            'address_id' => $address->id
        ]);

        $transaction = Transaction::create([
            'transaction_id' => strtotime('now'),
            'amount' => $order->total,
            'payment_id' => strtotime('now'),
            'payment_type' => $request->payment,
            'status' => 'pending'
        ]);

        $order->update([
            'transaction_id' => $transaction->id
        ]);

        $getaway = $transaction->pay();


        if ($getaway->isSuccessful()) {
            $getaway->redirect();
        }


        return redirect()->back();
    }

    //start Order to 1C

    public static function product_1c($data)
    {
        $client = new Client();
        $ic_api = 'http://178.160.203.146:1728';
        //http://62.89.21.215:11556
        //http://178.160.203.146:1728 //live
        //http://192.168.150.159 //local
        $response = $client->request('post',
            $ic_api . "/Promas/hs/eshop/Order",
            [
                'blocking' => true,
                'headers' => array(
                    'Authorization' => 'Basic ' . base64_encode('Eshop:cY4meryb')
                ),
                'body' => $data
            ]
        );
    }

    public static function complete_order($completeOrder)
    {
//        $paymentDescription = '';
//        if ($completeOrder->payment == 1) {
            $paymentDescription = 'Բանկային քարտով';  // Bank transfer
//        } elseif ($completeOrder->payment == 2) {
//            $paymentDescription = 'Idram';  // Idram
//        }

        $c_data_arr = array(
            "TransactionDate" => date('Ymdhis'),
            "ClientName" => $completeOrder->first_name . ' ' . $completeOrder->last_name,
            "ClientID" => $completeOrder->user_id ?? '',
            "Address" => '',
            "Tell" => $completeOrder->phone . ', ' .  $completeOrder->phone1,
            "Email" => $completeOrder->email ?? '',
            "Note" => $completeOrder->notes ?? '',
            "Order_Number" => '#' . $completeOrder->invoice_no . '-' .  $paymentDescription,
            "ItemsList" => [],
        );
//        $c_data_arr = array(
//            "TransactionDate" => date('Ymdhis'),
//            "ClientName" => "",
//            "ClientID" => "",
//            "Address" => "",
//            "Tell" => $completeOrder["phone"] . ', ' .  $completeOrder["phone1"],
//            "Email" => "",
//            "Note" => "",
//            "Order_Number" => '#' . $completeOrder->invoice_no . '-' ,
//            "ItemsList" => [],
//        );
//        if (isset($completeOrder["first_name"])) {
//            $c_data_arr["ClientName"] = $completeOrder["first_name"] . ' ' . $completeOrder["last_name"];
//        }
//        if (isset($completeOrder["user_id"])) {
//            $c_data_arr["ClientID"] = $completeOrder["user_id"];
//        }
//        if (isset($completeOrder["email"])) {
//            $c_data_arr["Email"] = $completeOrder["email"];
//        }
//        if (isset($completeOrder["notes"])) {
//            $c_data_arr["Note"] = $completeOrder["notes"];
//        }


//        $product1C = new Product1C();
//        $items = $product1C->getItems();
//
//        foreach ($items as $item) {
//            $quantity = $item->getQuantity();
//            $price = number_format($item->getPrice(), 0, '.', '');
//            $productId = $item->getId();
//            $product = Product::where('id', $productId)->first();
//
//            $productData= [
//                'ItemID' => $product->item_id,
//                'Quantity' => $quantity,
//                'Price' => $price,
//            ];
//            $c_data_arr['ItemsList'][]= $productData;
//        }

//        if (!empty($completeOrder->shipping_cost)) {
//            $c_data_arr['ItemsList'][] = [
//                "ItemID" => "00-00013304",
//                "quantity" => 1,
//                "price" => number_format($completeOrder->shipping_cost, 0, '.', ''),
//            ];
//
//        }

        foreach ($completeOrder->items as $item) {
            $quantity = $item->pivot->quantity;
            $price = number_format($item->pivot->price, 0, '.', '');
            $productId = $item->id;
            $product = Product::find($productId);

            $productData = [
                'ItemID' => $product->item_id,
                'Quantity' => $quantity,
                'Price' => $price,
            ];
            $c_data_arr['ItemsList'][] = $productData;
        }

        if (!empty($completeOrder->shipping_cost)) {
            $c_data_arr['ItemsList'][] = [
                "ItemID" => "00-00013304",
                "Quantity" => 1,
                "Price" => number_format($completeOrder->shipping_cost, 0, '.', ''),
            ];
        }


        $addressId = $completeOrder->address->id;
        $address = Address::where('id', $addressId)->first();
        if ($address) {
            $street = $address->street;
            $home = $address->home;
            $city = $address->city;
            $states = $address->state;
        }

        $cities = State::where('id', $states)->first();
        $st = $cities->title;

        $reg = City::where('id', $city)->first();
        $ci = $reg->title;

        $c_data_arr["Address"] = $st . ' ' . $ci . ' ' . $street . ' ' . $home;
        $c_data_json = json_encode($c_data_arr);
        self::product_1c($c_data_json);

    }

    //end Order to 1C

}
