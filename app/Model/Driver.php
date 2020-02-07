<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{

    /**
     * Get the user's full name.
     *
     * @return string
     */
    public function getFullNameAttribute()
    {
        return "{$this->last_name} {$this->first_name} {$this->middle_name}";
    }

    public function flights()
    {
        return $this->belongsToMany('App\Model\Flight', 'driver_flight', 'flight_id', 'driver_id')
            ->withPivot('date');
    }


}
