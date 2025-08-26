<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\Translatable\HasTranslations;

class Category extends Model implements Sortable
{
    use HasFactory;
    use HasTranslations;
    use HasSlug;
    use SortableTrait;

    public $sortable = [
        'order_column_name' => 'sort_order',
        'sort_when_creating' => true,
        'nova_order_by' => 'DESC',
    ];

    public $translatable = ['title'];

    protected $casts = ['field_types' => 'array', 'filters' => 'array'];

    public function parent(){
        return $this->belongsTo(Category::class, 'parent_id', 'id');
    }

    public function children(){
        return $this->hasMany(Category::class, 'parent_id', 'id')->orderBy('sort_order', 'desc');
    }

    public function products(){
        return $this->hasMany(Product::class, 'category_id', 'id');
    }

    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }

    public function getFilters($key){
        $attributes = $this->products()->whereNotNull($key)
            ->select($key)
            ->groupBy($key)
            ->orderBy($key, 'desc')
            ->get();

        return $attributes;
    }

    public function getRelatedProductsAttribute(){
        return $this->products()
            ->inRandomOrder()
            ->take(4)
            ->get();
    }
}
