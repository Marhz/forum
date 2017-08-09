@component('profiles.activities.activity')
	@slot('heading')
		@if(get_class($activity->subject->favorited) == 'App\Thread')
			<a href="{{ $activity->subject->favorited->path() }}">
				{{ $profileUser->name }} favorited thread
				{{ $activity->subject->favorited->title }}
			</a>
		@else
			<a href="{{ $activity->subject->favorited->path() }}">
				{{ $profileUser->name }} favorited a reply on thread
				{{ $activity->subject->favorited->thread->title }}
			</a>
		@endif
	@endslot

	@slot('body')
    	{{ Illuminate\Support\Str::limit($activity->subject->favorited->body, 50) }}
	@endslot
@endcomponent