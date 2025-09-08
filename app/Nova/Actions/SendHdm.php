<?php

namespace App\Nova\Actions;

use App\Library\OnlineHdm;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Http\Requests\NovaRequest;

class SendHdm extends Action
{
    public $name = 'Send HDM';

    /**
     * Поля, которые Nova покажет в диалоге действия.
     * ВАЖНО: сигнатура должна принимать NovaRequest $request
     */
    public function fields(NovaRequest $request): array
    {
        return [
            Boolean::make('Send Email?', 'send_email')
                ->help('If enabled, we will send an email after creating the HDM.')
                ->default(false),
        ];
    }

    public function handle(ActionFields $fields, Collection $models)
    {
        $sendEmail = (bool) $fields->get('send_email', true);

        foreach ($models as $order) {
            if ($order->has_hdm) {
                return Action::danger("Order {$order->invoice_no} already has HDM.");
            }

            try {
                $hdm = new OnlineHdm($order->id);
                $hdm->createHdm($sendEmail);

            } catch (\Throwable $e) {
                return Action::danger("Failed to send HDM for {$order->invoice_no}: {$e->getMessage()}");
            }
        }

        return Action::message('HDM successfully sent.');
    }
}
