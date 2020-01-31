<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Notifications\Employee_History;  

use Illuminate\Notifications\Notifiable;

class Company extends Model
{

    use Notifiable;
    protected $fillable = [
        'name',
        'email',
     
    ];


    public function sites()
    {
       return $this->hasMany(Site::class);
    }
}
