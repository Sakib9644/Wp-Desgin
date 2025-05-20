<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Novelunitlessons extends Model
{
    //

     public function novel_unit_lists()
    {
        return $this->belongsTo(Novelunitlist::class, foreignKey: 'novel_unit_list');
    }
}
