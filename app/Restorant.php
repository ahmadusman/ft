<?php

namespace App;

use willvincent\Rateable\Rateable;
use App\MyModel;

class Restorant extends MyModel
{
    use Rateable;

    protected $fillable = ['name','subdomain', 'user_id', 'lat','lng','address','phone','logo','description','city_id'];
    protected $appends = ['alias','logom','icon','coverm'];
    protected $imagePath='/uploads/restorants/';

    protected $casts = [
        'radius' => 'array',
    ];

    protected $attributes = [
        'radius' => '{}'
    ];

    

    /**
     * Get the user that owns the restorant.
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function getAliasAttribute()
    {
        return $this->subdomain;
    }


    public function getLogomAttribute()
    {
        return $this->getImge($this->logo,config('global.restorant_details_image'));
    }
    public function getIconAttribute()
    {
        return $this->getImge($this->logo,str_replace("_large.jpg","_thumbnail.jpg",config('global.restorant_details_image')),"_thumbnail.jpg");
    }

    public function getCovermAttribute()
    {
        return $this->getImge($this->cover,config('global.restorant_details_cover_image'),"_cover.jpg");
    }

    public function categories()
    {
        return $this->hasMany('App\Categories','restorant_id','id')->where(['categories.active' => 1]);
    }

    public function hours()
    {
        return $this->hasOne('App\Hours','restorant_id','id');
    }
}
