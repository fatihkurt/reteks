<?php

namespace App\Model;

class PageTranslation extends \Illuminate\Database\Eloquent\Model
{

    protected $table = 'page_translation';

    protected $touches = ['page'];


    public function news()
    {
        return $this->belongsTo('App\Model\Page');
    }
}