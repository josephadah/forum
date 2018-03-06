{{-- editing --}}
<div class="panel panel-default" v-if="editing">
    <div class="panel-heading">
        <h3>Editing Thread</h3>
        <div class="form-group">
            <label>Title</label>
            <input class="form-control" v-model="form.title">
        </div>
    </div>

    <div class="panel-body">
        <div class="form-group">
            <label>Body</label>
            <textarea class="form-control" v-model="form.body"></textarea>
        </div>
    </div>

    <div class="panel-footer">
        <button class="btn btn-default btn-xs" @click="editing = true" v-if="! editing">Edit</button>
        <button class="btn btn-primary btn-xs" @click="update">Update</button>
        <button class="btn btn-danger btn-xs" @click="cancel">Cancel</button>

        @can ('update', $thread)
            <form method="POST" action="{{ route('threads.delete', [$thread->channel, $thread]) }}" style="display: inline;">
                {{ csrf_field() }}
                {{ method_field('DELETE') }}
                <button class="btn btn-danger btn-xs" type="submit">Delete Thread</button>
            </form>
        @endcan
    </div>
</div>

{{-- Not editing --}}
<div class="panel panel-default" v-else>
    <div class="panel-heading">
        <h3 v-text="title"></h3>
        <img src="{{ $thread->creator->avatar_path }}" alt="{{ $thread->creator->name }}" width="30" height="30" style="float:left; margin-right: 5px;">
        <p>by: <a href="{{ route('profile', $thread->creator->name) }}">{{ $thread->creator->name }}</a>, {{ $thread->created_at->diffForHumans() }} 
        </p>
    </div>

    <div class="panel-body" v-text="body"></div>

    <div class="panel-footer" v-if="authorize('owns', thread)">
        <button class="btn btn-default btn-xs" @click="editing = true">Edit</button>
    </div>
</div>