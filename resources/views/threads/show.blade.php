@extends('layouts.app')

@section('content')
    <thread-view inline-template :starter-replies-count="{{ $thread->replies_count }}">
        <div class="container">
            <div class="row">
                <div class="col-md-8 ">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <div class="level">
                                <h5 class="flex">
                                    <a href="{{ $thread->creator->path() }}">{{ $thread->creator->name }}</a>
                                    posted : {{ $thread->title }}                                
                                </h5>
                                <div class="level">
                                    @can ('update', $thread)
                                        <form method="POST" action="{{ $thread->path() }}">
                                            {{ csrf_field() }}
                                            {{ method_field('DELETE') }}
                                            <button type="submit" class="btn btn-link">Delete Thread</button>
                                        </form>
                                    @endcan
                                    @if(Auth::check())
                                        <favorite :subject="{{ $thread }}" type="threads"></favorite>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="panel-body">
                            {{ $thread->body }}
                        </div>
                    </div>
                    
                    <replies
                        @removed="repliesCount--"
                        @added="repliesCount++"
                    >
                    </replies>
                        
                </div>
                <div class="col-md-4">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <p>Published : {{ $thread->created_at->diffForHumans() }}</p>
                            <p>Author: <a href="">{{ $thread->creator->name }}</a></p>
                            <p><span v-text="repliesCount"></span> {{ str_plural('comment', $thread->replies_count) }}</p>
                            <subscribe-button :is-active="{{ json_encode($thread->isSubscribedTo) }}"></subscribe-button>
                        </div>
                    </div>
                </div>
            </div>
        
        </div>
    </thread-view>
@endsection
