@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <form method="POST" action="{{ route('threads.store') }}">
                {{ csrf_field() }}
                
                <h3 class="text-center">Create New Thread</h3>
                
                <div class="form-group">
                    <label for="title">Title:</label>
                    <input type="text" name="title" class="form-control" value="{{ old("title") }}" required>
                </div>

                <div class="form-group">
                    <label for="channel">Channel:</label>
                    <select class="form-control" name="channel_id" required>
                        <option>Select....</option>
                        @foreach($channels as $channel)
                            <option value="{{ $channel->id }}" {{ old("channel_id") == $channel->id ? "selected" : "" }} >
                                {{ $channel->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="body">Body:</label>
                    <textarea name="body" class="form-control" rows="6">{{ old("body") }}</textarea>
                </div>
                
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
                
                @include('partials.formErrors')

            </form>
        </div>
    </div>
</div>
@endsection
