<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Translatable\HasTranslations;

class ProductAttribute extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
}
