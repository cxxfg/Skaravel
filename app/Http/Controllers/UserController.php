<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests\PostFormRequest;
use App\Http\Controllers\Controller;
use App\User;
use App\Posts;
use App\Comments;
use App\Http\Requests;

class UserController extends Controller
{
	// Displays top 5 latest user posts
    public function userPosts($id) {
    	$posts = Posts::where('author_id',$id)->where('active',1)->orderBy('created_at','desc')->paginate(5);
    	$title = User::find($id)->name;
    	return view('home')->withPosts($posts)->withTitle($title);
    }

    // Displays all of the users posts
    public function userPostsAll(Request $request) {
    	$user = $request->user();
    	$posts = Posts::where('author_id',$user->id)->orderBy('created_at','desc')->paginate(5);
    	$title = $user->name;
    	return view('home')->withPosts($posts)->withTitle($title);
    }

    // Displays draft posts of active user
    public function userDrafts(Request $request) {
    	$user = $request->user();
    	$posts = Posts::where('author_id',$user->id)->where('active',0)->orderBy('created_at','desc')->paginate(5);
    	$title = $user->name;
    	return view('home')->withPosts($posts)->withTitle($title);
    }

    // Display list of all users
    public function showAllUsers() {
        $users = User::all();
        return view('users.list')->with('users',$users);
    }

    // Retrieve edit about me page
    public function editAbout(Request $request, $id) {
        $data['user'] = User::find($id);
        $data['about'] = $data['user']->aboutme;
        // Just in case auth. user is trying to edit another about page
        if (!($request->user()->name == $data['user']->name)) {
            return redirect('/')->withErrors("You have insufficient permissions.");
        } else {
            return view('users.about',$data);
        }
    }

    // Publish about me information
    public function updateAbout(Request $request, $id) {
        $user = User::find($id);
        $about = $request->get('about');
        $user->aboutme = $about;
        $user->save();  
        return redirect('/user/'.$id)->withMessage("About page successfully updated");
    }

    // Profile for the user
    public function profile(Request $request, $id) {
    	$data['user'] = User::find($id);
    	if (!$data['user'])
    		return redirect('/');
    	if ($request -> user() && $data['user']->id == $request -> user()->id) {
    		$data['author'] = true;
    	} else {
    		$data['author'] = null;
    	}

        $data['admin'] = $data['user']->role == 'admin';
        $data['aboutme'] = $data['user']->aboutme;
    	$data['comments_count'] = $data['user']->comments->count();
	    $data['posts_count'] = $data['user']->posts->count();
	    $data['posts_active_count'] = $data['user']->posts->where('active',1)->count();
	    $data['posts_draft_count'] = $data['posts_count'] - $data['posts_active_count'];
	    $data['latest_posts'] = $data['user']->posts->where('active', 1)->take(5);
	    $data['latest_comments'] = $data['user']->comments->take(5);
	    return view('users.profile', $data);
    }
}
