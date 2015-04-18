<?php

namespace App\Model;

class PageGallery extends \Illuminate\Database\Eloquent\Model
{

    protected $table = 'page_gallery';

    protected $guarded = array('id', 'page_id');

    public $timestamps = false;


    public function page()
    {
        return $this->belongsTo('App\Model\Page', 'page_id', 'id');
    }
}