<?php

namespace App\Model;

class ContactForm extends \Illuminate\Database\Eloquent\Model
{

    protected $table = 'career_application';

    protected $guarded = array('id');

    public $timestamps = true;
}