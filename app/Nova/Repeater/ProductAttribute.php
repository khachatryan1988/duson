<?php

namespace App\Nova\Repeater;

use Laravel\Nova\Fields\Repeater\Repeatable;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\ID;
use Spatie\Translatable\HasTranslations;

class ProductAttribute extends Repeatable
{
    use HasTranslations;

    public $translatable = ['value'];

    public static $model = \App\Models\ProductAttribute::class;
    /**
     * Get the fields displayed by the repeatable.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::hidden('id'),
            Text::make('Key'),
            Text::make('Value', 'value')->translatable()->fillUsing(function ($request, $model, $attribute, $requestAttribute) {
                $request->request->remove('brend');
            }),
        ];
    }
}
