@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            @forelse ($threads as $thread)
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="level">
                            <h4 class="flex">
                                @if(Auth::check() && $thread->hasUpdateFor(auth()->user()))
                                    <strong>
                                        <a href="{{ $thread->path() }}">
                                            {{ $thread->title }}
                                        </a>                                        
                                    </strong>
                                @else
                                    <a href="{{ $thread->path() }}">
                                        {{ $thread->title }}
                                    </a>
                                @endif
                            </h4>
                            <a href="{{ $thread->path() }}">
                                <strong>{{ $thread->replies_count }} {{ str_plural('reply', $thread->replies_count) }}</strong>
                            </a>
                        </div>
                    </div>

                    <div class="panel-body">
                        <div class="body">
                            {{ $thread->body }}
                        </div>
                        <hr/>
                    </div>
                </div>
            @empty
                <p>No threads here let!</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
