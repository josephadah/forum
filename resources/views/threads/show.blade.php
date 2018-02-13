@extends('layouts.app')

@section('content')
<thread-view :initial-replies-count="{{ $thread->replies_count }}" inline-template>
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3>{{ $thread->title }}</h3>
                        <p>by: <a href="{{ route('profile', $thread->creator->name) }}">{{ $thread->creator->name }}</a>, {{ $thread->created_at->diffForHumans() }} 
                        </p>

                        @can ('update', $thread)
                            <form method="POST" action="{{ route('threads.delete', [$thread->channel, $thread]) }}">
                                {{ csrf_field() }}
                                {{ method_field('DELETE') }}
                                <button class="btn btn-danger btn-xs" type="submit">Delete Thread</button>
                            </form>
                        @endcan
                    </div>

                    <div class="panel-body">
                        {{ $thread->body }}
                    </div>
                </div>

                    <div class="row">
                        <div class="col-md-10 col-md-offset-1">
                            <replies :data="{{ $thread->replies }}" 
                                @added="replies_count++"
                                @removed="replies_count--"></replies>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</thread-view>
@endsection