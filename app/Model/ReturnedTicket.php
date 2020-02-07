<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ReturnedTicket extends Model
{
    protected $table = 'returned_tickets';

    public function tickets()
    {
        return $this->hasMany('App\Model\Ticket');
    }

    public function ticket()
    {
        return $this->belongsTo('App\Model\Ticket');
    }
}
