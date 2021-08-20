<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Order extends Model
{
    protected $table = 'orders';

    protected $fillable = ['fee','fee_value','static_fee','vatvalue'];

    public function restorant()
    {
        return $this->belongsTo('App\Restorant');
    }

    public function driver()
    {
        return $this->hasOne('App\User','id','driver_id');
    }

    public function address()
    {
        return $this->hasOne('App\Address','id','address_id');
    }

    public function client()
    {
        return $this->hasOne('App\User','id','client_id');
    }

    public function status()
    {
        return $this->belongsToMany('App\Status','order_has_status','order_id','status_id')->withPivot('user_id','created_at','comment')->orderBy('order_has_status.id','ASC');;
    }

    public function items()
    {
        return $this->belongsToMany('App\Items','order_has_items','order_id','item_id')->withPivot(['qty','extras','vat','vatvalue','variant_price','variant_name']);
    }


    public function getTimeFormatedAttribute()
    {
        $parts=explode('_',$this->delivery_pickup_interval);
        if(count($parts)<2){
            return "";
        }
        
        $hoursFrom=(int)(($parts[0]/60)."");
        $minutesFrom=$parts[0]-($hoursFrom*60);
        
        
        $hoursTo=(int)(($parts[1]/60)."");
        $minutesTo=$parts[1]-($hoursTo*60);
        

        $format="G:i";
        if(env('TIME_FORMAT',"24hours")=="AM/PM"){
            $format="g:i A";
        }
        $from=date($format, strtotime($hoursFrom.":".$minutesFrom));
        $to=date($format, strtotime($hoursTo.":".$minutesTo));

        


        return $from." - ".$to;
    }

}
