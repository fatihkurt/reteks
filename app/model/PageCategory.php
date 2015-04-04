<?php

namespace App\Model;

class PageCategory extends \Illuminate\Database\Eloquent\Model
{

    protected $table = 'page_category';

    public function pages() {

        return $this->hasMany('App\Model\Page', 'category_id');
    }
}