

@extends('layouts.master')

@section('css')
    @cssLoader("engineer-index")
@stop

@section('js')
    @jsLoader("test")
@stop

@section('content')

<div class="page-header">
    <h1>Team Members</h1>
</div>

<div id="test"></div>

@stop
