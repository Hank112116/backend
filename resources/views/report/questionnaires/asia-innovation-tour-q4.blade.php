@extends('layouts.master')
@include('layouts.macro')
@section('jqui')
    @include('layouts.jqui')
@stop
@section('css')
    <link rel="stylesheet" href="/css/user-list.css">
@stop
@section('js')
    <script src='/js/list.js'></script>
    <script src='/js/event-questionnaire.js'></script>
@stop

@section('content')
    <div class="page-header">
        <h1>Not yet</h1>
    </div>
    <div class="row text-center">
        <a class="btn-main" href="/report/events">Back</a>
    </div>
@stop
