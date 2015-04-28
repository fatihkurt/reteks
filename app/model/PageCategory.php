<?php

namespace App\Model;

class PageCategory extends \Illuminate\Database\Eloquent\Model
{

    protected $table = 'page_category';

    protected $guarded = array('id');

    public $timestamps = false;

    public function pages() {

        return $this->hasMany('App\Model\Page', 'category_id')->orderBy('ordernum');
    }

    public function pageContent($pageId) {

        return \App\Model\PageTranslation::where('page_id', '=', $pageId)->where('lang', '=', $this->getLang)->first();
    }

    public function defaultPage($lang) {

        return \App\Model\PageTranslation::where('page_id', '=', $this->default_page_id)->where('lang', '=', $lang)->first();
    }

    public function getName($lang) {

        return $this->{"name_$lang"};
    }

    public function getBannerName($lang) {

        return $this->{"banner_name_$lang"} ?: $this->getName($lang);
    }
}