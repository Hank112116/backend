@extends('layouts.master')

@section('css')
    @cssLoader("engineer-index")
@stop

@section('content')

<ul id='tab' class="nav nav-tabs">
  	<li class="active"><a href="#query-log" data-toggle="query-log">QUERY LOG</a></li>
    <li><a href="#jobs" data-toggle="jobs">JOBS</a></li>
</ul>

<div class="tab-content">
  	<div class="tab-pane fade in active" id="query-log">
  		@include('engineer.query')
  	</div>

    <div class="tab-pane fade" id="jobs">
        @if(App::environment() == 'dev')
          <a href="/engineer/update-hwtrek-dev-db" class="btn btn-primary">UPDATE HWTREK DEV DB</a>
        @endif
    </div>
</div>

@stop
