<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gradecategory extends Model
{
    //
     public function grade(){
        return $this->belongsTo(Grade::class,'garde_id');
    }
}
