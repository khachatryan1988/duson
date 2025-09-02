<?php

namespace App\Nova\Actions;

use App\Models\Order;
use App\Library\OnlineHdm; // убедись в корректном неймспейсе
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;

class ReverseHdm extends Action
{
    public $name = 'Reverse HDM';

    public function handle(ActionFields $fields, Collection $models)
    {
        foreach ($models as $model) {
            /** @var Order $model */
            if (!$model->has_hdm) {
                return Action::danger("Order {$model->invoice_no} has no HDM.");
            }

            try {
                $hdm = new OnlineHdm($model->id);
                $hdm->reverseHdm();

                $model->forceFill(['el_hdm' => null])->save();
            } catch (\Throwable $e) {
                return Action::danger("Failed to reverse HDM for {$model->invoice_no}: {$e->getMessage()}");
            }
        }

        return Action::message('HDM successfully reversed.');
    }
}
