<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Campus extends Model
{
    //
    public function districts()
    {
        return $this->belongsTo(District::class, 'districts_id', 'id');
    }

    public function user()
    {
        return $this->hasMany(User::class)->whereHas('roles', function ($query) {
            $query->where('name', 'Teacher');
        });
    }
}
