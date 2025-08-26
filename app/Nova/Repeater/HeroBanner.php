<?php

namespace App\Nova\Repeater;

use Laravel\Nova\Fields\Repeater\Repeatable;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Outl1ne\NovaMediaHub\Nova\Fields\MediaHubField;

class HeroBanner extends Repeatable
{
    /**
     * Get the fields displayed by the repeatable.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            MediaHubField::make('Image', 'image')
                ->defaultCollection('Hero banner'),
            Text::make('Title')->translatable(),
            Text::make('CTA text')->translatable(),
            Text::make('CTA url')->translatable(),
        ];
    }
}
