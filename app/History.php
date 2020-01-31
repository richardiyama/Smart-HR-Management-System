<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    protected $fillable = [
        'type',
        'emp_id',
        'employee_name',
        'employee_position'
     
    ];
}
