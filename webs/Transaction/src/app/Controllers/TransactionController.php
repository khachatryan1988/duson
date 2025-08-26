<?php

namespace Webs\Transaction\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Webs\Transaction\Models\Transaction;
use Webs\Transaction\Payment;

class TransactionController
{
    private $getaway;
    private $purchase;

    /**
     * Handles universal callback from payment gateway
     */
    public function callback(Request $request, $type)
    {
        $payment = new Payment($type);
        $this->getaway = $payment->getaway;
        $this->getaway->setCallbackData($request);

        $this->purchase = $this->getaway->completePurchase()->send();
        $this->updateTransaction();

        return redirect('/transaction/' . $request->orderID);
    }

    /**
     * Idram callback with payment status
     */
    public function idramCallback(Request $request, $status)
    {
        $transaction = $this->findTransaction($request->input('EDP_BILL_NO'));

        if (!$transaction) {
            Log::warning("Transaction not found for Idram callback", $request->all());
            return response('Transaction not found', 404);
        }

        $transaction->status = $status;
        $transaction->save();

        return redirect()->to('/transaction/' . $transaction->transaction_id);
    }

    /**
     * Idram server-side result validation
     */
    public function idramResult(Request $requestData)
    {
        $gateway = new Payment('Idram');
        $purchase = $gateway->completePurchase()->send();

        if ($purchase->isSuccessful()) {
            $data = [
                'EDP_AMOUNT' => $requestData->get('EDP_AMOUNT'),
                'EDP_BILL_NO' => $requestData->get('EDP_BILL_NO'),
                'EDP_PAYER_ACCOUNT' => $requestData->get('EDP_PAYER_ACCOUNT'),
                'EDP_TRANS_ID' => $requestData->get('EDP_TRANS_ID'),
                'EDP_TRANS_DATE' => $requestData->get('EDP_TRANS_DATE'),
            ];

            $transaction = $this->findTransaction($data['EDP_BILL_NO']);

            if (!$transaction) {
                Log::error("Transaction not found for Idram result", $data);
                return response('Transaction not found', 404);
            }

            $transaction->payment_id = $data['EDP_TRANS_ID'];
            $transaction->result = $data;
            $transaction->status = 'success';
            $transaction->save();

            return response('OK', 200);
        }

        Log::error("IDRAM purchase failed", $requestData->all());
        return response('FAILED', 400);
    }

    /**
     * Updates transaction with purchase result
     */
    protected function updateTransaction()
    {
        $reference = $this->purchase->getTransactionReference();
        $transaction = $this->findTransaction($reference);

        if ($transaction) {
            $transaction->status = $this->purchase->isSuccessful() ? 'success' : 'error';
            $transaction->result = $this->purchase->getData();
            $transaction->save();
        } else {
            Log::warning("Transaction not found during update", ['reference' => $reference]);
        }
    }

    /**
     * Initiates payment process
     */
    public function payTransaction(Request $request, $transactionId)
    {
        $transaction = $this->findTransaction($transactionId);

        if ($transaction) {
            $getaway = $transaction->pay();

            if ($getaway->isSuccessful()) {
                return $getaway->redirect();
            }

            return redirect()->back()->withErrors(['message' => 'Վճարումը չհաջողվեց։']);
        }

        return abort(404);
    }

    /**
     * Utility: Find transaction by ID
     */
    protected function findTransaction($id)
    {
        return Transaction::where('transaction_id', $id)->first();
    }
}
