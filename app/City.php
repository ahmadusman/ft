<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\MyModel;

class City extends MyModel
{
    use SoftDeletes;
    protected $table = 'cities';
    protected $imagePath='/uploads/settings/';

    protected $fillable = ['name','alias', 'image','header_title','header_subtitle'];
    protected $appends = ['logo'];

    public function getLogoAttribute()
    {
        return $this->getImge($this->image,config('global.restorant_details_image'));
    }
    
}
