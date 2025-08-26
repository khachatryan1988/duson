<?php

namespace Webs\Transactions\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Omnipay\Omnipay;
use Symfony\Component\HttpFoundation\ParameterBag;
use Webs\Transactions\Models\Transaction;
use Webs\Transactions\Payment;

class IdramController
{
    public function idramResult(Request $request){
        $payment = new Payment('idram');
        $this->getaway = $payment->getaway;

        Log::channel('idramLog')->info("✅ Result log", $request->all());

        $purchase = $this->getaway->completePurchase()->send();

        // Do the rest with $purchase and response with 'OK'
        if ($purchase->isSuccessful()) {
            $billingNo = $request->get('EDP_BILL_NO');
            $transaction = Transaction::where('transaction_id', $billingNo)->first();

            if($transaction){
                $transaction->payment_code = $request->get('EDP_TRANS_ID');
                $transaction->result = $request->all();
                $transaction->save();

                die('OK');
            }else{
                die('Error! No transaction found');
            }
        }

        die('ERROR');
    }

    public function idramCallback(Request $request, $status){
        $transactionId = $request->input('EDP_BILL_NO');

        $transaction = Transaction::where('transaction_id', $transactionId)->first();
        if ($transaction) {
            $transaction->status = ($status === 'success') ? 'completed' : 'declined';
            $transaction->save();
            $orderNumber = $transaction->billing->order->order_number;
            return redirect()->away(config('app.frontend_url') . '/order/' . $orderNumber);
        }
        return redirect()->away(config('app.frontend_url'));
    }
}
