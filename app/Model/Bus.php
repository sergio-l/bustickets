<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Bus extends Model
{
    
    public function getImagesAttribute($value)
    {
        return preg_split('/,/', $value, -1, PREG_SPLIT_NO_EMPTY);
    }
    public function setImagesAttribute($images)
    {
        $this->attributes['images'] = implode(',', $images);
    }

    /*
    public function images()
    {
        return $this->belongsToMany('App\Model\Image');
    }*/

    public function flights()
    {
        return $this->hasMany('App\Model\Flight');
    }
}
