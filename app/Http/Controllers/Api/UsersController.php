<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
	/**
	 * retrieve users from database
	 *
	 * @return     json
	 */
    public function index()
    {
    	$search = request('name');

    	return User::where('name', 'LIKE', "$search%")
    			->take(5)
    			->pluck('name');
    }
}
