@extends('layouts.master')
@include('layouts.macro')

@section('css')
    @cssLoader('inbox')
@stop

@section('js')
    <script type="text/javascript" src="/react/inbox.js"></script>
@stop

@section('content')

    <div class="page-header">
        <h1>Latest Topics</h1>
    </div>

    <div id="inbox" class="inbox-container"></div>
@stop



