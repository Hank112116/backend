@extends('layouts.master')
@include('layouts.macro')

@section('css')
    <link rel="stylesheet" href="{{ LinkGen::assets('css/inbox.css') }}">
@stop

@section('js')
    <script src="{{ LinkGen::assets('react/inbox.js') }}"></script>
@stop

@section('content')

    <div class="page-header">
        <h1>Latest Topics</h1>
    </div>

    <div id="inbox" class="inbox-container"></div>
@stop



