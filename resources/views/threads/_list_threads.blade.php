@forelse($threads as $thread)
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="level">
                <div class="flex">
                    <h4>
                        <a href="{{ $thread->path() }}">
                            @if(auth()->check() && $thread->hasUpdatesFor())
                                <strong>{{ $thread->title }}</strong>
                            @else
                                {{ $thread->title }}
                            @endif
                        </a>
                    </h4>
                    <h5>Posted by: <a href="{{ route('profile', $thread->creator) }}">{{ $thread->creator->name }}</a></h5>
                </div>
                <a href="{{ $thread->path() }}">
                    <strong>{{ $thread->replies_count }} {{ str_plural('reply', $thread->replies_count) }}</strong>
                </a>
            </div>
        </div>

        <div class="panel-body">
                <article>
                    <div class="body">
                		<p>{{ $thread->body }}</p>
                    </div>
                </article>
        </div>
        <div class="panel-footer">
            <p>{{ $thread->visits()->count() }} {{ str_plural('Visit', $thread->visits()->count()) }}</p>
        </div>
    </div>
@empty
    <h3 class="text-center">No Threads on this channel yet</h3>
@endforelse