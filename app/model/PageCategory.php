<?php

namespace App\Model;

class PageCategory extends \Illuminate\Database\Eloquent\Model
{

    protected $table = 'page_category';

    public function pages() {

        return $this->hasMany('App\Model\Page', 'category_id');
    }

    public function defaultPage($lang) {

        return \App\Model\PageTranslation::where('page_id', '=', $this->default_page_id)->where('lang', '=', $lang)->first();
    }
}