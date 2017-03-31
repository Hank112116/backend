@extends('layouts.master')
@include('layouts.macro')
@section('jqui')
    @include('layouts.jqui')
@stop
@section('css')
    <link rel="stylesheet" href="{{ LinkGen::assets('css/user-list.css') }}">
@stop
@section('js')
    <script src="{{ LinkGen::assets('js/list.js') }}"></script>
    <script src="{{ LinkGen::assets('js/event-questionnaire.js') }}"></script>
@stop

@section('content')
    <div class="page-header"></div>
    <div class="row text-center">
        <h4>
            Not yet
        </h4>
    </div>
@stop
