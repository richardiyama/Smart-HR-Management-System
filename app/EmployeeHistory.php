<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmployeeHistory extends Model
{
    protected $fillable = [
        'type',
        'emp_id',
        'employee_name',
        'employee_company',
        'employee_department',
        'employee_site' 
     
    ];
}
