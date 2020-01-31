<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Site extends Model
{


    protected $fillable = [
        'name',
        'company_id',
     
    ];
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function department()
    {
        return $this->hasMany(Department::class);
    }

    public function preemployement()
    {
        return $this->belongsTo(PreEmployement::class);
    }
}
