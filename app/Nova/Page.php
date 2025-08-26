<?php

namespace App\Nova;

use App\Nova\Repeater\Benefits;
use App\Nova\Repeater\HeroBanner;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Repeater;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use Mostafaznv\NovaCkEditor\CkEditor;
use Outl1ne\NovaMediaHub\Nova\Fields\MediaHubField;
use Whitecube\NovaFlexibleContent\Flexible;

class Page extends Resource
{
    public static $trafficCop = false;
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Page>
     */
    public static $model = \App\Models\Page::class;


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
            Text::make('Title')->sortable()->translatable()->rules(['required']),
            Text::make('Slug')->hideWhenCreating(),

//            Flexible::make('Content')
//                ->addLayout('Hero banner', 'hero-banner', [
//                    MediaHubField::make('Image', 'image')
//                        ->defaultCollection('Hero banner'),
//                    Text::make('Title')->translatable(),
//                    Text::make('CTA text')->translatable(),
//                    Text::make('CTA url')->translatable(),
//                ])
            Flexible::make('Content')
                ->addLayout('Hero banner', 'hero-banner', [
                    Flexible::make('Slides', 'slides')
                        ->addLayout('Slide', 'slide', [
                            MediaHubField::make('Image', 'image')->defaultCollection('Hero banner'),
                            Text::make('Title')->translatable(),
                            Text::make('CTA text')->translatable(),
                            Text::make('CTA url')->translatable(),
                        ])
                        ->button('Add Slide')
                        ->fullWidth(),
                ])
                ->addLayout('Benefits', 'benefits', [
                    Flexible::make('Benefit')
                        ->addLayout('Benefits', 'benefits', [
                            MediaHubField::make('Icon', 'icon')
                                ->defaultCollection('Icons'),
                            Text::make('Title')->translatable(),
                        ])
                        ->fullWidth()
                        ->button('Add benefit'),
                ])
                ->addLayout('Categories', 'categories', [
                    Text::make('Title')->translatable(),
                    Flexible::make('Categories')
                        ->addLayout('Item', 'item', [
                            MediaHubField::make('Image', 'image')
                                ->defaultCollection('categories'),
                            Text::make('CTA text')->translatable(),
                            Text::make('CTA url')->translatable(),
                        ])
                        ->fullWidth()
                        ->button('Add category'),
                ])
                ->addLayout('Text block', 'text-block', [
                    Text::make('Title', 'title')->translatable(),
                    CkEditor::make('Content', 'body')->translatable(),
                ])
                ->addLayout('Three columns', 'three-columns', [
                    Flexible::make('Columns')
                        ->addLayout('Text Column', 'text-column', [
                            Text::make('Title', 'title')->translatable(),
                            CkEditor::make('Content', 'body')->translatable(),
                        ])
                        ->fullWidth()
                        ->button('Add column'),
                ])
                ->addLayout('Image banner', 'image-banner', [
                    MediaHubField::make('Image', 'image')
                        ->defaultCollection('banners'),
                ])
                ->addLayout('Reviews', 'reviews', [
                    Text::make('Title', 'title')->translatable(),
                    Flexible::make('Reviews')
                        ->addLayout('Review', 'review', [
                            Text::make('Reviewer', 'reviewer')->translatable(),
                            Textarea::make('Review', 'review')->translatable(),
                        ])
                        ->fullWidth()
                        ->button('Add review'),
                ])
                ->collapsed(),

            Text::make('Seo Title')->translatable(),
            Text::make('Seo Description')->translatable(),

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
