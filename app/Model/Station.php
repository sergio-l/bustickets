<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Station extends Model
{
    public function flights()
    {
        return $this->belongsToMany('App\Model\Flight', 'flight_station', 'station_id', 'flight_id')
            ->withPivot('departure', 'arrival');
    }

    public function priceFlight()
    {
        return $this->belongsTo('App\Model\FlightPrice');
    }

    public function user()
    {
        return $this->hasOne('App\Model\User');
    }

   
}
