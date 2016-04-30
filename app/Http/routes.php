<?php
// In this file we route HTTP requests to specific controllers

// The front page 
Route::get('/','PostController@index');

// Home page for logged in users
Route::get('home', ['as'=>'home','uses'=>'PostController@index']);

// Defines authentication, registration and login routes
Route::auth();

// If the user is authenticated (logged in)
Route::group(['middleware'=>['auth']], function(){
	// Post controller
	Route::get('new-post','PostController@create'); //show new post form
	Route::post('new-post','PostController@store'); //save new post
	Route::get('edit/{slug}','PostController@edit'); //edit post form
	Route::post('update','PostController@update'); //update post
	Route::get('delete/{id}','PostController@delete'); //delete post
	// User controller
	Route::get('myposts','UserController@userPostsAll'); //display all of users posts
	Route::get('mydrafts','UserController@userDrafts'); //display drafted posts
	Route::get('user/{id}/edit-about','UserController@editAbout'); //display change about me page
	Route::post('user/{id}/edit-about','UserController@updateAbout');//submit about me changes
	// Comment controller
	Route::post('comment/add','CommentsController@add'); //add comment
	Route::post('comment/delete/{id}','CommentsController@delete'); //delete comment
	// Show all users and their information
	Route::get('userlist','UserController@showAllUsers');
});

// Controller options if non-authenticated (view-only) 
Route::get('user/{id}','UserController@profile')->where('id','[0-9]+'); //user profile
Route::get('user/{id}/posts','UserController@userPosts')->where('id','[0-9]+');//list of posts
Route::get('/{slug}',['as'=>'post','uses'=>'PostController@show'])->where('slug', '[A-Za-z0-9-_]+');//single post