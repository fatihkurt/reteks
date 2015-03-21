<?php

namespace App\Model;

class NewsTranslation extends \Illuminate\Database\Eloquent\Model
{

    protected $table = 'news_translation';

    protected $touches = ['news'];


    public function news()
    {
        return $this->belongsTo('App\Model\News');
    }
}