@extends('layouts.app')

@section('content')
	<div class="container">
		<div class="row">
			<div class="col-md-8 col-md-offset-2">
				<div class="page-header">
					<h1>
						{{ $profileUser->name }}
						<small>Since {{ $profileUser->created_at->diffForHumans() }}</small>
					</h1>
				</div>
				@forelse ($activities as $date => $activities)
					<h3 class="page-header">{{ $date }}</h3>
					@foreach ($activities as $activity)
						@if (view()->exists("profiles.activities.{$activity->type}"))
							@include("profiles.activities.{$activity->type}")
						@endif
					@endforeach
				@empty
					<p>No activity for this dude yet</p>
				@endforelse
			</div>
		</div>
	</div>
@endsection