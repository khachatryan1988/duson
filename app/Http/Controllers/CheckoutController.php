<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutRequest;
use App\Library\OnlineHdm;
use App\Library\Product1C;
use App\Mail\SendMail;
use App\Models\Address;
use App\Models\City;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\State;
use App\Traits\CartTrait;
use Illuminate\Http\Request;
use Cart;
use Auth;
use Illuminate\Support\Facades\Mail;
use Webs\Transaction\Models\Transaction;

class CheckoutController extends Controller
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

//            Product1C::complete_order($order);
//            $hdmGenerator = new OnlineHdm($order->id);
//            $hdmGenerator->createHdm();


        $order->update([
            'transaction_id' => $transaction->id
        ]);

        $getaway = $transaction->pay();


        if ($getaway->isSuccessful()) {
            $getaway->redirect();
        }


        return redirect()->back();
    }

    public function showTransaction($orderNumber)
    {
        $order = Order::where('invoice_no', $orderNumber)->firstOrFail();

        try {
            if ($order->transaction->status == 'success') {
                Product1C::complete_order($order);

                $hdmGenerator = new OnlineHdm($order->id);
                $hdmGenerator->createHdm();

                $order->statuses()->attach($this->statuses['processing']);
            } else if ($order->transaction->status == 'error') {
                $order->statuses()->attach($this->statuses['payment-failed']);
            }
        } catch (\Exception $e) {
//            file_put_contents(storage_path('logs/order_processing.log'),
//                'Failed to process Order ' . $orderNumber . ': ' . $e->getMessage() . ' at ' . now()->format('d.m.Y H:i:s') . "\n", FILE_APPEND);

//            return view('order')->with([
//                'order' => $order,
//                'orderStatus' => 'failed',
//                'errorMessage' => 'Order processing failed. Please try again later.',
//            ]);
        }
//        if ($order->total >= 10000) {
            try {
                Mail::to($order->email)
                    ->bcc('domusonline.web@gmail.com')
                    ->send(new SendMail($orderNumber, $order->transaction->status));
            } catch (\Exception $e) {
                file_put_contents(storage_path('logs/mail.log'),
                    'Failed to send email for Order ' . $orderNumber . ': ' . $e->getMessage() . ' at ' . now()->format('d.m.Y H:i:s') . "\n", FILE_APPEND);

                return view('order')->with([
                    'order' => $order,
                    'emailStatus' => 'failed',
                    'errorMessage' => $e->getMessage(),
                ]);
            }
//        }
        return view('order')->with([
            'order' => $order,
        ]);
    }

}
