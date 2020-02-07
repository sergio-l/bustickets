<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    protected $table = 'types';

    public function flights()
    {
        return $this->belongsToMany('App\Model\Flight', 'flight_type');
    }
}
