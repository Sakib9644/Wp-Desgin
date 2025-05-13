<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    //
    public function grade(){
        return $this->belongsTo(MainCategory::class,'main_cat_id');
    }
}
