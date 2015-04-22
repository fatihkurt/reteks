<?php

namespace App\Model;

class CareerApplicationEducation extends \Illuminate\Database\Eloquent\Model
{

    protected $table = 'career_application_education';

    protected $guarded = array('id', 'application_id');

    public $timestamps = false;
}