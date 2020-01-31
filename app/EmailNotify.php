<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmailNotify
{
    public $user;
    public $bvn;
    public $subject;
    public $body;
    public $email;
    public $url;
    public $transfer_employees;
    public $confirmation; // 1 for confirmation and 0 for all activities including the confirmation.
}
