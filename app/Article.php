<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Article extends Model
{
    /*use Searchable;

    /*public function searchableAs()
    {
        return 'title';
    }*/

    protected $fillable = ['title', 'img', 'alias', 'text', 'desc', 'keywords', 'metaDesc', 'category_id'];

    public function user(){
        return $this->belongsTo('App\User');
    }

    public function category(){
        return $this->belongsTo('App\Category');
    }

    public function comments(){
        return $this->hasMany('App\Comment');
    }
}
