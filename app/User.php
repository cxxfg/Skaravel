<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    //The attributes that are mass assignable.
    protected $fillable = [
        'name', 'email', 'password',
    ];

     //The attributes that should be hidden for arrays. 
    protected $hidden = [
        'password', 'remember_token',
    ];

    // Associates user with posts
    public function Posts() {
        return $this->hasMany('App\Posts','author_id');
    }

    // Associates user with comments
    public function Comments() {
        return $this->hasMany('App\Comments','from_user');
    }

    // Checks privileges whether user can post
    public function canPost() {
        $role = $this->role;
        if ($role == 'author' || $role == 'admin') {
            return true;
        } else {
            return false;
        }
    }

    // Checks whether user is an admin
    public function isAdmin() {
        $role = $this->role;
        if ($role == 'admin') {
            return true;
        } else {
            return false;
        }
    }
}
