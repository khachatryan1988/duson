<?php

namespace Webs\Transaction\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Webs\Transaction\Payment;

class Transaction extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    /**
     * @return LogOptions
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnly(['*']);
    }

    protected $guarded = ['id'];

    protected $casts = ['result' => 'array'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function payment(){
        return $this->belongsTo('Webs\Transaction\Models\Payment', 'payment_type', 'id');
    }

    /**
     * Make a payment
     */
    public function pay()
    {
        $payment = new Payment($this->payment->key);
        return $payment->makePayment($this);
    }
}
