<?php

namespace App\Model;

class ListLanguageLevel extends \Illuminate\Database\Eloquent\Model
{

    protected $table = 'list_language_level';

    public $timestamps = false;

    function getName($lang) {

        return $this->{"name_$lang"};
    }
}