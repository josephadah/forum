@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <h2 class="text-center">Recent Threads</h2>
            @include('threads._list_threads')
            {{ $threads->links() }}
        </div>

        {{-- side bar --}}
        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panelheading">
                    Quick Search
                </div>
                <div class="panel-body">
                    <form method="POST" action="/search">
                        {{ csrf_field() }}
                        <input type="text" name="q" placeholder="Search anything....">
                        <button type="submit" class="btn btn-primary btn-sm">Search</button>
                    </form>
                </div>
            </div>
        	<div class="panel panel-default">
        		<div class="panel-heading">
        			<h3>Trending Threads</h3>
        		</div>
        		<div class="panel-body">
        			<ul class="list-group">
	        			@foreach($trending as $thread)
							<li class="list-group-item">
								<a href="{{ url($thread->path) }}">{{ $thread->title }}</a>
							</li>
	        			@endforeach
	        		</ul>
        		</div>
        	</div>
        </div>
    </div>
</div>
@endsection