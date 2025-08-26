<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Webs\Transaction\Models\Transaction;

class Order extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function items()
    {
        return $this->belongsToMany(Product::class, 'order_product', 'order_id', 'product_id')
            ->withPivot('quantity', 'price', 'total');
    }

    public function transaction(){
        return $this->belongsTo(Transaction::class);
    }

    public function address(){
        return $this->belongsTo(Address::class);
    }

    public function getAddressInfoAttribute(){
        return $this->address->street . ' ' . $this->address->home . ' ' . City::find($this->address->city)->title . ' ' . State::find($this->address->state)->title;
    }

    public function getRouteKeyName()
    {
        return 'invoice_no';
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function statuses(){
        return $this->belongsToMany(OrderStatus::class, 'order_status_history', 'order_id', 'status_id')->withPivot('created_at')->orderBy('order_status_history.created_at', 'desc');
    }

    public function getStatusAttribute(){
        return $this->statuses()->orderBy('id', 'desc')->first();
    }
}
