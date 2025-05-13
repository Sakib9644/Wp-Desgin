<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TecaherGrade extends Model
{
   protected $table = 'tecaher_grades'; 

    // Relationship with User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship with Grade
    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }
}
