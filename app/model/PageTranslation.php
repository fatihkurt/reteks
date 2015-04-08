<?php

namespace App\Model;

class PageTranslation extends \Illuminate\Database\Eloquent\Model
{

    protected $table = 'page_translation';

    public $timestamps = false;

    protected $touches = ['page'];


    protected $guarded = array('id', 'page_id');


    public function page()
    {
        return $this->belongsTo('App\Model\Page', 'page_id', 'id');
    }
}