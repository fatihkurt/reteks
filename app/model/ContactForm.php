<?php

namespace App\Model;

class ContactForm extends \Illuminate\Database\Eloquent\Model
{

    protected $table = 'contact_form';

    protected $guarded = array('id');

    public $timestamps = true;
}