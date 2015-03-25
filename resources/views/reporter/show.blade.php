@extends('layouts.master')

@section('css')
@cssLoader('home')
@stop

@section('content')

    {!! $encrypted !!}

@stop