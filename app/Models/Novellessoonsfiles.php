<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Novellessoonsfiles extends Model
{
    //

     public function lesson()
    {
        return $this->belongsTo(Novelunitlessons::class, foreignKey: 'lesson_id');
    }
}
