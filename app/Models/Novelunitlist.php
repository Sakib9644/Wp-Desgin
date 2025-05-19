<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Novelunitlist extends Model
{
    //

     public function novel()
    {
        return $this->belongsTo(Novel::class, 'novel_id');
    }
}
