@extends('layouts.master')

@section('css')
    @cssLoader('landing-refer')
@stop

@section('js')
    @jsLoader('landing-project-selector')
@stop

@section('content')
<div class="page-header">
    <h1>REFERENCE PROJECT</h1>
</div>

<div id="manufacturer" class="row">
    <div class="row search-bar">

        <div class="col-md-4">
            {!! Form::open(['action' => ['LandingController@findReferenceProject'], 
                           'method' => 'POST', 'class' => 'js-search-form']) !!}

                <div class="input-group">
                    {!! Form::text('id', '', 
                        ['placeholder'=> "Add project by project id", 
                         'class'=>"form-control search_id"]) !!}
                    <span class="input-group-btn">
                        <button class="btn btn-default js-btn-search" type="button">Go!</button>
                    </span>
                </div>

            {!! Form::close() !!}
        </div>

    </div>

    {!! Form::open([ 'action' => ['LandingController@updateReferenceProject'], 
                    'method' => 'POST']) !!}
    
        <div id="block-group" class="row refer-blocks">
            @foreach ($refers as $refer)
                @include('landing.refer-block', ['refer' => $refer, 'project' => $refer->project])
            @endforeach
        </div>

        <div class="btn-block">
            <button class="btn-sassy btn-submit">UPDATE</button>    
        </div>
        
    {!! Form::close() !!}
</div>

@stop