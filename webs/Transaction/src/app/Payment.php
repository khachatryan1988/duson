<?php
namespace Webs\Transaction;

use Omnipay\Omnipay;
use Webs\Transaction\Models\Transaction;
use Webs\Transaction\Models\Payment as PaymentMethod;

class Payment {

    private $paymentType;

    private $driver;

    public $getaway;

    private $purchase;

    public function __construct($driverName)
    {
        $this->driver = config('getaway.drivers.' . $driverName);

        $this->paymentType = $driverName;

        $this->setGetaway();
    }

    public function setGetaway (){
        $gateway = Omnipay::create($this->driver['name']);

        foreach($this->driver as $key => $value){
            if(method_exists($gateway, 'set' . $key)){
                $gateway->{'set' . $key}($value);
            }
        }

        $this->getaway = $gateway;
    }

    public function makePayment($transaction){

        $this->getaway->setAmount($transaction->amount);
        $this->getaway->setTransactionId($transaction->transaction_id);
        $this->purchase = $this->getaway->purchase()->send();

        if ($this->purchase->isRedirect()) {
            $transaction->payment_id = $this->purchase->getPaymentId();
            $transaction->save();
        }

        return $this->purchase;
    }
}
