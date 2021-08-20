<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Akaunting\Money\Currency;
use Akaunting\Money\Money;
use Illuminate\Database\Eloquent\SoftDeletes;

class Options extends Model
{
    use SoftDeletes;
    protected $table = 'options';
    protected $fillable = ['name','options','item_id'];

    public function item()
    {
        return $this->belongsTo('App\Items');
    }
}
