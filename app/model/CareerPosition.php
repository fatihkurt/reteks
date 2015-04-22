<?php

namespace App\Model;

class CareerPosition extends \Illuminate\Database\Eloquent\Model
{

    protected $table = 'career_position';

    protected $guarded = array('id');

    public $timestamps = true;

    function getName($lang) {

        return $this->{"name_$lang"};
    }
}