<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Posts extends Model
{
	// Used for prevent inserting/updating columns.
    protected $guarded = [];

    // Author of post
    public function Author() {
    	return $this->belongsTo('App\User','author_id');
    }

    // Comments to the post
    public function Comments() {
    	return $this->hasMany('App\Comments','on_post');
    }
}
