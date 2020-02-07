<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class UserHistory extends Model
{
   protected $table = 'user_history';

   public function user()
   {
      return $this->belongsTo('App\Model\User');
   }

   public function order()
   {
      return $this->belongsTo('App\Model\Order');
   }
}
