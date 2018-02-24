<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;

class RegisterConfirmationsController extends Controller
{
    public function index()
    {
    	try {
    		User::where('confirmation_token', request('token'))
    		->firstOrFail()
    		->confirm();
    	} catch (\Exception $e) {
    		return redirect('/threads')->with('flash', 'Unknown token.');
    	}

    	return redirect('/threads')->with('flash', 'Your Account is now Confirmed.');
    }
}
