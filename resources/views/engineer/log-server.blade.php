@extends('layouts.master')

@section('css')
    <link rel="stylesheet" href="/css/engineer-index.css">
@stop

@section('content')
    <div class="page-header">
        <h1>Log Server</h1>
    </div>
    {{ session()->get('logpass') }}
@stop