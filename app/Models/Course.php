<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    //
      public function gradecategory(){
        return $this->belongsTo(Gradecategory::class,'grade_category');
    }
}
