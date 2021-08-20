<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{
    protected $table = 'categories';

    public function items()
    {
        return $this->hasMany('App\Items','category_id','id');
    }

    public function restorant()
    {
        return $this->belongsTo('App\Restorant');
    }
}
