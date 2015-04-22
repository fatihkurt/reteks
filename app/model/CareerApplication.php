<?php

namespace App\Model;

class CareerApplication extends \Illuminate\Database\Eloquent\Model
{

    use \App\Plugin\ModelValidation;

    protected $table = 'career_application';

    protected $guarded = array('id');

    public $timestamps = true;

    protected $rules = array(
        'name' => 'required|alpha|min:3',
    );
}