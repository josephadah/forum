@extends('layouts.app')

@section('content')

	<div class="container">
		<div class="row">
			<div class="col-md-6 col-md-offset-3">
				<div class="page header">
					<avatar-form :profile-user="{{ $profileUser }}"></avatar-form>
				</div>
				@forelse($activities as $date => $activity)

					<h3 class="page-header">{{ $date }}</h3>

					@foreach($activity as $record)
						@if(view()->exists("profiles.activities.{$record->type}"))
							@include("profiles.activities.{$record->type}", ['activity' => $record])
						@endif
					@endforeach
				@empty
					<p>There is no activity for this user</p>
				@endforelse
			</div>
		</div>

	</div>

@endsection