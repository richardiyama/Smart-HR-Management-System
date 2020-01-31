<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PreEmployement extends Model
{

    /**
     * The database table used by the model. -- this is auto generated
     *
     * @var string
     */
    protected $table = 'pre_employments';

    protected $fillable = [
        'job_title',
        'section',
        'site',
        'position_status',
        'start_date',
        'amount',
        'request_supervisor',
        'project_manager_approval',
        'hr_manager_approval',
        'project_manager_approval_date',
        'hr_manager_approval_date',
        'pre_emp_code',

     
    ];
    

    public function site()
    {
        return $this->hasMany(Site::class);
    }

    public function position()
    {
        return $this->hasMany(Position::class);
    }

    public function department()
    {
        return $this->hasMany(Department::class);
    }

    
    

    public function positionstatus()
    {
        return $this->hasMany(PositionStatus::class);
    }

}
