<?php

use Illuminate\Database\Eloquent\Model;

class Articles extends Model
{
	public $timestamps = false;

    public function comments()
    {
        return $this->hasMany('Comments', 'article_id', 'id');
    }
}