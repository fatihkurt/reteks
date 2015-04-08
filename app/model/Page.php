<?php

namespace App\Model;

class Page extends \Illuminate\Database\Eloquent\Model
{

    protected $table = 'page';

    protected $guarded = array('id');

    public function contents() {

        return $this->hasMany('App\Model\PageTranslation', 'page_id');
    }


    public function content($lang) {

        $this->contents()->where('lang', '=', $lang)->first();
    }


    public function category()
    {
        return $this->belongsTo('App\Model\PageCategory', 'category_id', 'id');
    }
}