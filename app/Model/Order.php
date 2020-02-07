<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public function tickets()
    {
        return $this->hasMany('App\Model\Ticket');
    }

    public function countTicket()
    {
        return $this->tickets()->selectRaw('order_id, count(*) as tcount')
            ->groupBy('order_id');
    }

    public function flight()
    {
        return $this->belongsTo('App\Model\Flight');
    }

    public function saleStation()
    {
        return $this->belongsTo('App\Model\Station', 'sale_station_id');
    }

    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    public function user()
    {
        return $this->belongsTo('App\Model\Order');
    }
}
