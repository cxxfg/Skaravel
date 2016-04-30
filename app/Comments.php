<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comments extends Model
{
	// Used for prevent inserting/updating columns.
    protected $guarded = [];

    // Author of comment
    public function Author() {
    	return $this->belongsTo('App\User','from_user');
    }

    // The post which the comments belong to
    public function Post() {
    	return $this->belongsTo('App\Posts','on_post');
    }
}
