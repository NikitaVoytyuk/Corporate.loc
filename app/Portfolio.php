<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Portfolio extends Model
{
    protected $fillable = ['title', 'img', 'alias', 'text', 'keywords', 'metaDesc', 'filter_alias'];

    //
    public function filter(){
        return $this->belongsTo('App\Filter', 'filter_alias', 'alias');
    }

    public function user(){
        return $this->belongsTo('App\User');
    }
}
