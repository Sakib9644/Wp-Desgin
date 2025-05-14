<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    protected $table = "districts";
    //
    public function country(){
        return $this->belongsTo(Country::class);
    }
    public function campus(){
        return $this->hasMany(Campus::class,'districts_id');
    }
}
