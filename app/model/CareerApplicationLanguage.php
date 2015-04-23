<?php

namespace App\Model;

class CareerApplicationLanguage extends \Illuminate\Database\Eloquent\Model
{

    use \App\Plugin\ModelHelper;

    protected $table = 'career_application_language';

    protected $guarded = array('id');

    public $timestamps = false;
}