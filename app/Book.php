<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = ['title', 'cover', 'authors', 'year'];

    public function tags()
    {
        return $this->belongsToMany('App\Tag');
    }
}
