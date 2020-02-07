<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{

    protected $table = 'tickets';

    public function getSumaAttribute()
    {
        return $this->price + $this->baggage_price;
    }

    public function getFullNameAttribute()
    {
        return "{$this->last_name} {$this->first_name} {$this->middle_name}";
    }

    public function flight()
    {
        return $this->belongsTo('App\Model\Flight', 'flight_id');
    }
    
    public function order()
    {
        return $this->belongsTo('App\Model\Order', 'order_id');
    }
    

    public function stationA()
    {
        return $this->belongsTo('App\Model\Station', 'stationA_id');
    }

    public function stationB()
    {
        return $this->belongsTo('App\Model\Station', 'stationB_id');
    }

    public function scopeStation($query, $field, $station)
    {
        return $query->where($field, '=', $station);
    }

    public function scopeOrStation($query, $field, $station)
    {
        return $query->OrWhere($field, '=', $station);
    }


    public function scopeDate($query, $date)
    {
        return $query->whereDate('created_at', '=', $date);
    }


    public function scopeDateDuration($query, $start, $end)
    {
        $start = date('Y-m-d', strtotime($start));
        $end   = $end ? date('Y-m-d', strtotime($end)) : \Carbon\Carbon::now();

        return $query->whereBetween('created_at', array($start, $end));
    }

    public function returnedTicket()
    {
        return $this->hasOne('App\Model\ReturnedTicket');
    }

}
