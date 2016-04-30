<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Posts;
use App\Comments;
use App\User;
use Redirect;
use App\Http\Controllers\Controller;
use App\Http\Requests\PostFormRequest;

class CommentsController extends Controller
{
    public function add(Request $request) {
    	$input['from_user'] = $request->user()->id;
	    $input['on_post'] = $request->input('on_post');
	    $input['body'] = $request->input('body');
	    $slug = $request->input('slug');
	    Comments::create( $input );
	    return redirect($slug)->with('message', 'Comment published');
    }
}
