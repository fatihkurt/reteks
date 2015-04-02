<?php

namespace App\Model;

class Page extends \Illuminate\Database\Eloquent\Model
{

    protected $table = 'page';

    public function contents() {

        return $this->hasMany('App\Model\PageTranslation', 'page_id');
    }


    public function content($lang) {

        $this->contents()->where('lang', '=', $lang)->first();
    }
}