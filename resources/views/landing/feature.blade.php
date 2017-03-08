@extends('layouts.master')

@section('jqui')
    @include('layouts.jqui')
@stop

@section('css')
    <link rel="stylesheet" href="{{ LinkGen::assets('css/landing-feature.css') }}">
@stop

@section('js')
    <script src="{{ LinkGen::assets('js/landing-project-selector.js') }}"></script>
@stop

@section('content')
<div class="page-header">
    <h1>FEATURES</h1>
</div>

<div id="feature">
    <div class="col-md-11 col-md-offset-1">
        <div class="cover-wrapper" style="background-image: url(https://dev-backend.hwtrek.com/images/feature-list.png)">

        </div>

        <div class="cover-zooim-in">Hover to extend</div>
    </div>
    <div class="row col-md-offset-1">
        @foreach ($types as $type) 
            <div class="col-md-3">
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
    <div class="row col-md-offset-1">
        <div class="col-md-4">
            @foreach($features->statistics->statisticsData() as $item)
                {{ $item['text'] }} <span class="badge">{{ $item['count'] }}</span>
                @if (!$loop->last)
                    |
                @endif
            @endforeach
        </div>
    </div>
    <hr>
    <div class="row">
        <div id="block-group">
            @foreach ($features as $feature)
                @include('landing.feature-block', ['feature' => $feature])
            @endforeach
        </div>

        <div class="btn-block">
            <button class="btn-sassy btn-submit">UPDATE</button>
        </div>
    </div>
</div>
@include('landing.dialog.edit-feature')
@stop

