<?php

namespace App\Model;


class CareerPosition extends \Illuminate\Database\Eloquent\Model
{

    use \App\Plugin\ModelHelper,
        \Illuminate\Database\Eloquent\SoftDeletes;

    protected $table = 'career_position';

    protected $guarded = array('id');

    public $timestamps = true;

    protected $dates = ['deleted_at'];

    function getName($lang) {

        return $this->{"name_$lang"};
    }

    function getDetail($lang) {

        return $this->{"detail_$lang"};
    }
}