<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class FlightPrice extends Model
{
    protected $table = 'flight_price';

    public function flights()
    {
        return $this->belongsToMany('App\Model\Flight', 'flight_price');
    }

    public function stationA()
    {
        return $this->belongsTo('App\Model\Station', 'stationA_id');
    }

    public function stationB()
    {
        return $this->belongsTo('App\Model\Station', 'stationB_id');
    }
}
