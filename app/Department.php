<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = [
        'name',
        'site_id',
     
    ];

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function preemployement()
    {
        return $this->belongsTo(PreEmployement::class);
    }
}
