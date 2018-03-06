<?php

namespace App\Http\Controllers;

use App\Channel;
use App\Filters\ThreadFilters;
use App\Thread, App\Trending;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class ThreadsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
        $this->middleware('must-be-confirmed', ['only' => 'store']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Channel $channel, ThreadFilters $filters, Trending $trending)
    {
        $threads = $this->getThreads($channel, $filters);

        if (request()->wantsJson()) {
            return $threads;
        }

        return view('threads.index', [
            'threads' => $threads, 
            'trending' => $trending->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('threads.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required | spamfree',
            'body'  => 'required | spamfree',
            'channel_id' => 'required|exists:channels,id'
        ]);

        $slug = (new Thread)->makeSlug();

        $thread = Thread::create([
            'user_id' => auth()->id(),
            'channel_id' => $request->channel_id,
            'title' => $request->title,
            'body' => $request->body, 
            'slug' => $slug
        ]);

       return redirect($thread->path())
                ->with('flash', 'Thread Created Successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function show($channel, Thread $thread, Trending $trending)
    {
        if(auth()->check()){
            auth()->user()->readThread($thread);
        }

        $trending->push($thread);
        
        $thread->visits()->record();

        return view('threads.show', compact('thread'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function edit(Thread $thread)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function update($channel, Thread $thread)
    {
        $this->authorize('update', $thread);
        
        $thread->update(request()->validate([
            'title' => 'required | spamfree',
            'body'  => 'required | spamfree',
        ]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function destroy($channel, Thread $thread)
    {
        $this->authorize('update', $thread);
        
        // $thread->replies()->delete();

        $thread->delete();

        return redirect('/threads');
    }


    protected function getThreads($channel, $filters)
    {
        if($channel->exists){
            $threads = $channel->threads()->latest();
        } else {
            $threads = Thread::latest();
        }

        $threads = $threads->filter($filters)->paginate(20);

        return $threads;
    }
}
