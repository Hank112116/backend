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
        <div class="cover-wrapper" style="background-image: url(/images/feature-list.png)">

        </div>

        <div class="cover-zooim-in">Hover to extend</div>
    </div>
    <div class="row col-md-offset-1">
        {!! Form::open(['action' => ['LandingController@findFeatureEntity', ''],
                               'method' => 'POST', 'class' => 'form-inline js-search-form']) !!}
            <div class="form-group">
                {!! Form::select('type',[
                    ''          => 'Select one Object type',
                    'expert'    => 'Expert',
                    'project'   => 'Project',
                    'solution'  => 'Solution',
                ], '',['class'=>'form-control search_type']) !!}

                {!! Form::number('id', '',
                    ['placeholder'=> 'Add Object Id',
                     'class'=>"form-control search_id"]) !!}
            </div>
            <button class="btn btn-default js-btn-search" type="button">Go!</button>
        {!! Form::close() !!}
    </div>

    <div class="row col-md-offset-1">
        <h6>
            @foreach($feature_statistics->statisticsData() as $item)
                {{ $item['text'] }} <span class="badge">{{ $item['count'] }}</span>
                @if (!$loop->last)
                    |
                @endif
            @endforeach
        </h6>
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
