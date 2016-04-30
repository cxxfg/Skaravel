<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Requests\PostFormRequest;
use App\Http\Controllers\Controller;
use App\Posts;
use App\User;
use Redirect;

class PostController extends Controller
{
    public function welcome() {
        return view('welcome');
    }

	// Return with the 5 latest blog posts on the home page
    public function index() {
    	$posts = Posts::where('active',1)->orderBy('created_at','desc')->paginate(5);// Fetch 5 latest blog posts
    	$title = "Latest posts";// page heading
    	return view('home')->withPosts($posts)->withTitle($title);
    }

    // Create blog post
    public function create(Request $request) {
    	if ($request->user()->canPost()) {
    		return view('posts.create');
    	} else {
    		return redirect('/')->withErrors("You have insufficient permissions to create post");
    	}
    }

    // Either publish or draft the blog post
    public function store(PostFormRequest $request) {
    	$post = new Posts();
	    $post->title = $request->get('title');
	    $post->body = $request->get('body');
	    $post->slug = str_slug($post->title);
	    $post->author_id = $request->user()->id;
	    if($request->has('save'))
	    {
	      $post->active = 0;
	      $message = 'Post saved successfully';            
	    }            
	    else 
	    {
	      $post->active = 1;
	      $message = 'Post published successfully';
	    }
	    $post->save();
	    return redirect('edit/'.$post->slug)->withMessage($message);
    }

    // Edit blog post
    public function edit(Request $request,$slug) {
    	$post = Posts::where('slug',$slug)->first();
    	if ($post && ($request->user()->id == $post->author_id || $request->user()->isAdmin())) {
    		return view('posts.edit')->with('post',$post);
    	} else {
    		return redirect('/')->withErrors("You have insufficient permissions to edit post");
    	}
    }

    // Update blog post
    public function update(Request $request) {
    	$post_id = $request->input('post_id');
    	$post = Posts::find($post_id);
    	// If the post exists, session user matches author_id or is an admin
    	if ($post && ($post->author_id == $request->user()->id || $request->user()->isAdmin())) {
    		$title = $request->input('title');
    		$slug = str_slug($title);

    		//Check whether the title already exists
    		$duplicate = Posts::where('slug',$slug)->first();
    		if ($duplicate) {
    			if ($duplicate->id != $post_id) {
    				return redirect('edit/'.$post->slug)->withErrors('Title already exists')->withInput();
    			} else {
    				$post->slug = $slug;
    			}
    		}

    		// Post update content
    		$post->title = $title;
    		$post->body = $request->input('body');

    		// The post can either be published or drafted
    		if ($request->has('save')) {
    			$post->active = 0;
    			$message = 'Post saved successfully';
    			$landing = $post->slug;
    		} else {
    			$post->active = 1;
    			$message = 'Post updated successfully';
    			$landing = $post->slug;
    		}
    		$post->save();
    		return redirect($landing)->withMessage($message);

    	} else {
    		return redirect('/')->withErrors("You have insufficient permissions to update post");
    	}
    }

    // Delete blog post
    public function delete(Request $request, $id) {
    	$post = Posts::find($id);
    	// If the post exists, session user matches author_id or is an admin
    	if ($post && ($post->author_id == $request->user()->id || $request->user()->is_admin())) {
    		$post->delete();
    		$data['message'] = 'Post deleted successfully';
    	} else {
    		$data['errors'] = "You have insufficient permissions to delete post";
    	}
    	return redirect('/')->with($data);
    }

    // Show blog post
    public function show($slug) {
    	$post = Posts::where('slug',$slug)->first();//fetch post from database with slug as argument
    	if (!$post) {
    		return redirect('/')->withErrors("The page you requested was not found");//if there is no post, return error
    	}
    	$comments = $post->comments;
        // withPost ekki withPosts (eintala ekki fleirtala)
    	return view('posts.show')->withPost($post)->withComments($comments);
    }
}
