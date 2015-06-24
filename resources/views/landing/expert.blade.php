@extends('layouts.master')

@section('jqui')
    @include('layouts.jqui')
@stop
@section('css')
	@cssLoader('landing-expert')
@stop

@section('js')
    @jsLoader('landing-expert')
@stop
@section('content')

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
             
            <ul id="sortable">
              @foreach ($experts as $expert)             
                  @include('landing.expert-block', 
                    ['user' => $expert->entity, 
                     'description' => $expert->description])
              @endforeach
            </ul>

        </div>
        <div class="btn-block">
            <button class="btn-sassy btn-submit" type="button">UPDATE</button>    
        </div>
</div>

@stop

