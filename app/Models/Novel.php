<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Novel extends Model
{
    //

     public function grades_category()
    {
        return $this->belongsTo(Gradecategory::class, 'grade_category');
    }

      public function gradecategory(){
        return $this->belongsTo(Gradecategory::class,'grade_category');
    }
}
