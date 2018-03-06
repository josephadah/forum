@extends('layouts.app')

@section('header')
    <link href="/css/vendor/jquery.atwho.css" rel="stylesheet">
@endsection

@section('content')
<thread-view :thread="{{ $thread }}" inline-template v-cloak>
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                
                @include('threads._main_thread')

                    <div class="row">
                        <div class="col-md-10 col-md-offset-1">
                            <replies @added="replies_count++" @removed="replies_count--"></replies>
                        </div>
                    </div>
            </div>

            {{-- Side bar --}}
            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <p>This thread was published {{ $thread->created_at->diffForHumans() }}, by 
                            <a href="#">{{ $thread->creator->name }}</a>, 
                            and has <span v-text="replies_count"></span> {{ str_plural('comment', $thread->replies_count) }}</p>

                            <subscribe-button 
                            :subscription-status="{{ json_encode($thread->isSubscribeTo) }}" v-if="signedIn">
                            </subscribe-button>

                            <button class="btn btn-sm btn-default" @click="toggleLock" 
                                v-if="authorize('isAdmin')"
                                v-text="locked ? 'Unlock' : 'Lock'">Lock
                            </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</thread-view>
@endsection