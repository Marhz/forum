@component('profiles.activities.activity')
	@slot('heading')
	    <span class="flex">
			{{ $profileUser->name }} published 
			<a href="{{ $activity->subject->path() }}">
				{{ $activity->subject->title }}
			</a>
	    </span>
	@endslot

	@slot('body')
    	{{ $activity->subject->title }}
	@endslot
@endcomponent