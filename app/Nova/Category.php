<?php

namespace App\Nova;

use App\Nova\Repeater\CategoryField;
use App\Nova\Repeater\CategoryFiltersField;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Repeater;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Outl1ne\NovaSortable\Traits\HasSortableRows;

class Category extends Resource
{
    public static $trafficCop = false;

    use HasSortableRows {
        indexQuery as indexSortableQuery;
    }

    public static function indexQuery(NovaRequest $request, $query)
    {
        // Do whatever with the query
        // ie $query->withCount(['children', 'descendants', 'modules']);
        return parent::indexQuery($request, static::indexSortableQuery($request, $query));
    }

    public static $sortableCacheEnabled = true;

    public static function canSort(NovaRequest $request, $resource)
    {
        // Do whatever here, ie:
        // return user()->isAdmin();
        // return $resource->id !== 5;
        return true;
    }

    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Category>
     */
    public static $model = \App\Models\Category::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'title';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'title',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),
            Text::make('Title')->translatable()->rules(['required']),
            Text::make('Slug'),
            BelongsTo::make('Parent', 'parent', Category::class)->nullable(),
            Repeater::make('Filters')
                ->repeatables([
                    CategoryFiltersField::make(),
                ]),
            Repeater::make('Attributes', 'field_types')
                ->repeatables([
                    CategoryField::make(),
                ]),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function cards(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function filters(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function lenses(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function actions(NovaRequest $request)
    {
        return [];
    }
}
