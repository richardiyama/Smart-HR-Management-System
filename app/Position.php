<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    public function preemployement()
    {
        return $this->belongsTo(PreEmployement::class);
    }
}
