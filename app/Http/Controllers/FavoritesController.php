<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Reply, App\Favorite;

class FavoritesController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}

    public function show(Reply $reply) 
    {
        return redirect($reply->thread->path());
    }

    public function store(Reply $reply)
    {
    	$reply->favorite();
    }

    public function destroy(Reply $reply) 
    {
    	$reply->unfavorite();
    }
}
