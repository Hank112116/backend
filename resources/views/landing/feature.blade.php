@extends('layouts.master')

@section('css')
    <link rel="stylesheet" href="/css/landing-expert.css">
@stop

@section('js')
    <script src='/js/landing-project-selector.js'></script>
@stop

@section('content')
<div class="page-header">
    <h1>FEATURES</h1>
</div>

<div id="feature">
    <div class="row search-bar">

        @foreach ($types as $type) 
            <div class="col-md-4">
                {!! Form::open(['action' => ['LandingController@findFeatureEntity', $type], 
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

    {!! Form::open(['action' => ['LandingController@updateFeature'], 
        'method' => 'POST', 'class' => 'update-form', 'id' => 'feature-form']) !!}
        
        <div id="block-group">
            @foreach ($features as $feature)
                @include('landing.feature-block', ['feature' => $feature])
            @endforeach
        </div>
        
        <div class="btn-block">
            <button class="btn-sassy btn-submit">UPDATE</button>    
        </div>
    {!! Form::close() !!}
</div>

@stop

