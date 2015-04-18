<?php

namespace App\Model;

class Page extends \Illuminate\Database\Eloquent\Model
{

    protected $table = 'page';

    protected $guarded = array('id', 'category_id');

    public function contents() {

        return $this->hasMany('App\Model\PageTranslation', 'page_id');
    }


    public function content($lang) {

        return $this->contents()->where('lang', '=', $lang)->first();
    }


    public function category()
    {
        return $this->belongsTo('App\Model\PageCategory', 'category_id', 'id');
    }

    public function gallery() {

        return $this->hasMany('App\Model\PageGallery', 'page_id');
    }
}