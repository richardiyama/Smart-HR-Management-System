<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PositionStatus extends Model
{
 
    /**
     * The database table used by the model. -- this is auto generated
     *
     * @var string
     */
    protected $table = 'position_status';


    public function preemployement()
    {
        return $this->belongsTo(PreEmployement::class);
    }

}
