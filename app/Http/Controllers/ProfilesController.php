<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User, App\Activity;

class ProfilesController extends Controller
{
    public function show(User $user) 
    {
    	return view('profiles.show', [
    		'profileUser' => $user,
    		'activities' => Activity::feed($user)
    	]);
    }

}
