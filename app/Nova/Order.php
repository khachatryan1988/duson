<?php

namespace App\Nova;

use App\Http\Controllers\CheckoutController;
use App\Library\OnlineHdm;
use App\Library\Product1C;
use App\Mail\SendAdminMail;
use App\Mail\SendMail;
use Carbon\Carbon;
use Carbon\Traits\Date;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\KeyValue;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Stack;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;

class Order extends Resource
{
    public static $trafficCop = false;
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Order>
     */
    public static $model = \App\Models\Order::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'invoice_no';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'invoice_no', 'id', 'first_name', 'last_name', 'email', 'phone', 'user.name'
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param \Laravel\Nova\Http\Requests\NovaRequest $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),
        ];
    }

    public function fieldsForIndex()
    {
        return [
            DateTime::make('Date', 'updated_at')->sortable()->displayUsing(function ($field) {
                return $field->format('D d-m-Y H:i:s');
            }),
            Text::make('Invoice no', 'invoice_no'),
            Text::make('User')->displayUsing(function ($field) {
                return $this->user ? $this->user->name : $this->first_name . ' ' . $this->last_name;
            }),
            Text::make('Email', 'email')->filterable(),
            Text::make('Phone', 'phone'),
            Text::make('Status', 'status.title'),
            Text::make('Total', 'total')->filterable(),
        ];
    }

    public function fieldsForUpdate()
    {
        return [
            Text::make('Invoice no', 'invoice_no')->readonly(),
            Select::make('Status')
                ->options(\App\Models\OrderStatus::pluck('title', 'id'))
                ->withMeta(['value' => $this->status->id])
                ->nullable(),
            Text::make('Sub total (AMD)', 'sub_total'),
            Text::make('Shipping cost (AMD)', 'shipping_cost'),
            Text::make('Grand total (AMD)', 'total'),
        ];
    }

    public function fieldsForDetail()
    {
        return [
            Panel::make('General', $this->generalFields()),
            Panel::make('Status history', $this->statusFields()),
            Panel::make('Products', $this->productsFields()),
            Panel::make('Totals', $this->totalFields()),
            Panel::make('Contacts', $this->contactFields()),
            Panel::make('Transaction', $this->transactionFields())
        ];
    }


    public function generalFields()
    {
        return [
            Text::make('Invoice no', 'invoice_no'),
            Select::make('Payment status', 'transaction.status')->options([
                'pending' => 'Waiting for payment',
                'error' => 'Not paid',
                'success' => 'Paid',
            ])->readonly(),
            BelongsTo::make('User', 'user', User::class),

        ];
    }

    public function statusFields()
    {
        $fields = [];
        $statuses = $this->statuses;
        if (!empty($statuses)) {
            foreach ($statuses as $status) {
                $fields[] = Text::make(Carbon::parse($status->pivot->created_at)->format('d.m.Y H:i:s'))->withMeta(['value' => $status->title])->readonly()->hideFromIndex();
            }
        }
        return $fields;
    }

    public function contactFields()
    {
        return [
            Text::make('First name', 'last_name')->hideFromIndex(),
            Text::make('Last name', 'first_name')->hideFromIndex(),
            Text::make('Email', 'email')->hideFromIndex(),
            Text::make('Phone', 'phone'),
            Text::make('Phone1', 'phone1'),
            Text::make('Address', 'address_info')->readonly()->hideFromIndex(),
            Text::make('Notes', 'notes')->hideFromIndex(),
            Text::make('HDM', 'el_hdm')->hideFromIndex(),
        ];
    }

    public function productsFields()
    {
        return [
            BelongsToMany::make('Products', 'items', Product::class)->fields(function ($request, $relatedModel) {
                return [
                    Text::make('Quantity'),
                    Text::make('Item Price', 'price'),
                    Text::make('Total'),

                ];
            })->hideCreateRelationButton(),

        ];
    }

    public function totalFields()
    {
        return [
            Text::make('Sub total (AMD)', 'sub_total')->readonly()->hideFromIndex(),
            Text::make('Shipping cost (AMD)', 'shipping_cost')->readonly()->hideFromIndex(),
            Text::make('Grand total (AMD)', 'total')->readonly(),
        ];
    }

    public function transactionFields()
    {
        return [
            KeyValue::make('Transaction', 'transaction.result')->rules('json')->readonly(),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param \Laravel\Nova\Http\Requests\NovaRequest $request
     * @return array
     */
    public function cards(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param \Laravel\Nova\Http\Requests\NovaRequest $request
     * @return array
     */
    public function filters(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param \Laravel\Nova\Http\Requests\NovaRequest $request
     * @return array
     */
    public function lenses(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param \Laravel\Nova\Http\Requests\NovaRequest $request
     * @return array
     */
    public function actions(NovaRequest $request)
    {
        return [];
    }

    public static function beforeUpdate(Request $request, $model)
    {
        if ($model->status->id != $request->status) {
            $model->statuses()->attach($request->status);

            if ($request->status == 2) {
                Product1C::complete_order($model);
                $hdm = new OnlineHdm($model->id);
                $hdm->createHdm();
//                if ($model->total >= 10000) {
//                    try {
//                        Mail::to($model->email)
//                            ->bcc('domusonline.web@gmail.com')
//                            ->send(new SendAdminMail($model->invoice_no));
//                    } catch (\Exception $e) {
//                        file_put_contents(storage_path('logs/mail.log'), 'Failed to send email for Order ' . $model->invoice_no . ': ' . $e->getMessage() . ' at ' . now()->format('d.m.Y H:i:s') . "\n", FILE_APPEND);
//
//                        return view('order')->with([
//                            'order' => $model->invoice_no,
//                            'emailStatus' => 'failed',
//                            'errorMessage' => $e->getMessage(),
//                        ]);
//                    }
//                }
            }
            if ($request->status == 8) {
                $hdm = new OnlineHdm($model->id);
                $hdm->reverseHdm();
            }
        }
        $request->request->remove('status');
    }



    public static function fillForUpdate(NovaRequest $request, $model)
    {
        if (method_exists(static::class, 'beforeSave')) {
            static::beforeSave($request, $model);
        }


        if (method_exists(static::class, 'beforeUpdate')) {
            static::beforeUpdate($request, $model);
        }



        if (method_exists(static::class, 'afterSave')) {
            $model::saved(function ($model) use ($request) {
                static::afterSave($request, $model);

            });

        }




        if (method_exists(static::class, 'afterUpdate')) {
            $model::saved(function ($model) use ($request) {
                static::afterUpdate($request, $model);

            });
        }



//       if ($model->status->id == 2) {
//
//            Product1C::complete_order($model);
//            $hdm = new OnlineHdm($model->id);
//            $hdm->createHdm();
//
//            Mail::to($model->email)->bcc('khachatur.khachatryan@domus.am')->send(new SendAdminMail($model->invoice_no));
//
//            }
//        if ($model->status->id == 8) {
//            $hdm = new OnlineHdm($model->id);
//            $hdm->reverseHdm();
//
//        }



        return static::fillFields(
            $request, $model,
            (new static($model))->updateFieldsWithoutReadonly($request)
        );


    }



}
