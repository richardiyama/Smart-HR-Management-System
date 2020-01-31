<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BlacklistedEmployeeApprovalComments extends Model
{

     /**
     * The database table used by the model. -- this is auto generated
     *
     * @var string
     */
    protected $table = 'blacklisted_employee_approval_comments';

    protected $fillable = [
        'comment',
        'employee_name',
        
     
    ];
}
