<?php

namespace App\Nova;

use App\Models\ProductAttribute;
use Ebess\AdvancedNovaMediaLibrary\Fields\Images;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;
use Mostafaznv\NovaCkEditor\CkEditor;
use Outl1ne\NovaMediaHub\Nova\Fields\MediaHubField;

class Product extends Resource
{
    public static $trafficCop = false;
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Product>
     */
    public static $model = \App\Models\Product::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public function title(){
        return $this->title . ' / ' . $this->item_id;
    }

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'title', 'item_id', 'id'
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
            Panel::make('General', $this->generalFields()),
            Panel::make('Attributes', $this->attributesFields()),
        ];
    }

    public function generalFields(){
        return [
            Text::make('Title')->translatable()->rules(['required']),
            Text::make('Slug')->hideFromIndex(),
            Text::make('Item ID')->rules(['required']),
            Text::make('Size'),
            BelongsTo::make('Parent Product', 'parent', Product::class)->nullable()->searchable()->hideFromIndex(),
            CkEditor::make('Description', 'description')->translatable()->hideFromIndex(),
            Number::make('Price'),
            Number::make('Quantity', 'pivot.quantity'),
            Number::make('Old price')->hideFromIndex(),
            Number::make('Avg price')->hideFromIndex(),
            Date::make('Promo start', 'price_start')->hideFromIndex(),
            Date::make('Promo end', 'price_end')->hideFromIndex(),
            Number::make('Quantity')->step('any')->hideFromIndex(),
            Text::make('Unit', 'unit')->hideFromIndex(),
            Text::make('ADGT', 'adgt')->hideFromIndex(),
            Text::make('Name_arm', 'name_arm')->hideFromIndex(),
            Text::make('Chashback', 'cashback_price')->hideFromIndex(),
//            CkEditor::make('Gift_mat', 'gift_mat')->translatable()->hideFromIndex(),
            BelongsTo::make('Category', 'category', Category::class)->nullable(),
            MediaHubField::make('Media', 'media')
                ->defaultCollection('products') // Define the default collection the "Choose media" modal shows
                ->multiple()
        ];
    }

    public function attributesFields(){
        $fields = [];

        if(!empty($this->category)){
            if(!empty($this->category->field_types)){
                foreach($this->category->field_types as $attr){
                    $field = $attr['fields'];
                    if($field['type'] == 'text'){
                        $fields[] = Text::make($field['title'], 'data->' . $field['key'])->translatable()->hideFromIndex();
                    }
                }

                return $fields;
            }
        }

        return $fields;
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
