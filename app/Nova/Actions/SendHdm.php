<?php

// app/Nova/Actions/SendHdm.php
namespace App\Nova\Actions;

use App\Models\Order;
use App\Library\OnlineHdm;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;

class SendHdm extends Action
{
    public $name = 'Send HDM';

    public function handle(ActionFields $fields, Collection $models)
    {
        foreach ($models as $order) {
            if ($order->has_hdm) {
                return Action::danger("Order {$order->invoice_no} already has HDM.");
            }

            try {
                $hdm = new OnlineHdm($order->id);
                $response = $hdm->createHdm();


            } catch (\Throwable $e) {
                return Action::danger("Failed to send HDM for {$order->invoice_no}: {$e->getMessage()}");
            }
        }

        return Action::message('HDM successfully sent.');
    }


}
