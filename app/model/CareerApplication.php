<?php

namespace App\Model;

class CareerApplication extends \Illuminate\Database\Eloquent\Model
{

    use \App\Plugin\ModelValidation,
        \App\Plugin\ModelHelper;

    protected $table = 'career_application';

    protected $guarded = array('id');

    public $timestamps = true;

    protected $rules = array(
        'position_id'   => 'required',
        'name'          => 'required|min:3',
        'title'         => 'required',
        'birthplace'    => 'required',
        'birthdate'     => 'required|date',
        'nation'        => 'required',
        'condition'     => 'required',
        'clean_record'  => 'required',
        'city'          => 'required',
        'state'         => 'required',
        'gsm'           => 'required',
        'email'         => 'required|email',
        'tc_number'     => 'digits:11',
        'driving_licence' => 'required',
    );

    public function setRulesCv() {

        $this->rules = array(
            'position_id'   => 'required',
            'name'          => 'required|min:3',
            'city'          => 'required',
            'state'         => 'required',
            'gsm'           => 'required',
            'email'         => 'required|email',
        );
    }


    public function position() {

        return $this->belongsTo('App\Model\CareerPosition', 'position_id', 'id');
    }

    public function languages() {

        return $this->hasMany('App\Model\CareerApplicationLanguage', 'application_id', 'id');
    }

    public function educations() {

        return $this->hasMany('App\Model\CareerApplicationEducation', 'application_id', 'id');
    }
}