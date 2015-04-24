<?php

namespace App\Model;

class ListLanguage extends \Illuminate\Database\Eloquent\Model
{

    protected $table = 'list_language';

    public $timestamps = false;

    function getName($lang) {

        return $this->{"name_$lang"};
    }
}