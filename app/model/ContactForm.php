<?php

namespace App\Model;

class ContactFormCategory extends \Illuminate\Database\Eloquent\Model
{

    protected $table = 'contact_form';

    protected $guarded = array('id');

    public $timestamps = true;
}