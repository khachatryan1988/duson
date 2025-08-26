<?php

use Outl1ne\NovaMediaHub\Models\Media;

function tr($field){
    $locale = app()->getLocale();

    if(!empty($field[$locale])){
        return $field[$locale];
    }else if(!empty($field['hy'])){
        return $field['hy'];
    }else if(!empty($field['en'])){
        return $field['en'];
    }

    return 'no translation';
}

function getImage($id = null){
    $image = Media::find($id);

    if($image) return $image->getUrl();

    return null;
}
