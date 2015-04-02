<?php

namespace App\Model;

class News extends \Illuminate\Database\Eloquent\Model
{

    protected $table = 'news';

    public function contents() {

        return $this->hasMany('App\Model\NewsTranslation');
    }


    public function content($lang) {

        $this->contents()->where('lang', '=', $lang)->first();
    }
}