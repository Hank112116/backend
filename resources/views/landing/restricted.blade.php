@extends('layouts.master')

@section('css')
    <link rel="stylesheet" href="{{ LinkGen::assets('css/landing-restricted.css') }}">
@stop

@section('js')
    <script src="{{ LinkGen::assets('js/landing-restricted.js') }}"></script>
@stop

@section('content')
    <div class="page-header">
        <h1>Low Priority List</h1>
    </div>

    <div id="restricted">
        <div class="row search-bar">
            @foreach($type_list as $type)
                <div class="col-md-3">
                    {!! Form::open(['action' => ['LandingController@addRestrictedObject', $type],
                                   'method' => 'POST', 'class' => 'js-search-form']) !!}

                    <div class="input-group">
                        {!! Form::text('id', '',
                            ['placeholder'=> "Add by {$type} Id",
                             'class'=>"form-control search_id",
                             'autocomplete' => 'off']) !!}

                        <span class="input-group-btn">
                        <button class="btn btn-default js-btn-search" type="button">Go!</button>
                    </span>
                    </div>

                    {!! Form::close() !!}
                </div>
            @endforeach
        </div>
        @include('landing.restricted-user', ['users' => $users])
        @include('landing.restricted-project', ['projects' => $projects])
        @include('landing.restricted-solution', ['solutions' => $solutions])
    </div>

@stop

