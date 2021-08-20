<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ratings extends Model
{

    function user(){
        return $this->belongsTo('App\User');
    }

    function order(){
        return $this->belongsTo('App\Order');
    }
}