@extends('layouts.master')

@section('css')
    @cssLoader("engineer-index")
@stop

@section('content')
    <div class="page-header">
        <h1>Log Server</h1>
    </div>
    {{ session()->get('logpass') }}
@stop