@extends('layouts.master')

@section('css')
	@cssLoader('landing-expert')
@stop

@section('js')
    @jsLoader('landing-expert')
@stop
@section('content')
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<div class="page-header">
<h1>EXPERT</h1>
</div>

<div id="expert">
    <div class="row search-bar">

        @foreach ($types as $type) 
            <div class="col-md-4">
                {!! Form::open(['action' => ['LandingController@findExpertEntity', $type], 
                               'method' => 'POST', 'class' => 'js-search-form']) !!}

                    <div class="input-group">
                        {!! Form::text('id', '', 
                            ['placeholder'=> "Add ".studly_case($type)." by ".studly_case($type)." Id", 
                             'class'=>"form-control search_id"]) !!}

                        <span class="input-group-btn">
                            <button class="btn btn-default js-btn-search" type="button">Go!</button>
                        </span>
                    </div>

                {!! Form::close() !!}
            </div>
        @endforeach

    </div>
        
        <div>
             
            <ul id="sortablettt">
              @foreach ($experts as $expert)
                  @include('landing.expert-block', ['user' => $expert->entity])

              @endforeach
            </ul>

        </div>
        <div class="btn-block">
            <button class="btn-sassy btn-submit" type="button">UPDATE</button>    
        </div>
</div>

@stop

