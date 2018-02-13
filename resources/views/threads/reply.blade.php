{{-- <reply :attributes="{{ $reply }}" inline-template v-cloak>
    <div id="reply-{{ $reply->id }}" class="panel panel-default">
        <div class="panel-heading level">
            <h5 class="flex">
                <a href="{{ route('profile', $reply->owner->name) }}">{{ $reply->owner->name }}</a> reply, {{ $reply->created_at->diffForHumans() }}
            </h5>
            
            <favorite :reply="{{ $reply }}" authcheck="{!! Auth::check() !!}"></favorite>

        </div>
        <div class="panel-body">
            <div v-if="editing">
                <div class="form-group">
                    <textarea class="form-control" rows="1" v-model="body"></textarea>
                </div>
                <button class="btn btn-primary btn-xs" @click="update">Update</button>
                <button class="btn btn-link btn-xs" @click="editing = false">Cancel</button>
            </div>
            <div v-else v-text="body">@{{ body }}</div>
            
        </div>
        @can('update', $reply)
            <div class="panel-footer level">
                <button class="btn btn-primary btn-xs mr-1" @click="editing = true">Edit</button>
                <button class="btn btn-danger btn-xs" @click="destroy">Delete</button>
            </div>
        @endcan
    </div>
</reply> --}}