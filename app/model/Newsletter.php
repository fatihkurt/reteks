<?php

namespace App\Model;

class Newsletter extends \Illuminate\Database\Eloquent\Model
{

    protected $table = 'newsletter';

    protected $guarded = array('email');



}