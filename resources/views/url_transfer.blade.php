@extends('layouts.master')

@section('css')
    @cssLoader('home')
@stop

@section('content')
	<div class="page-header">
	  <h1>Production HWTrek Project {{$project_id}} Links</h1>
	</div>

    @foreach($links as $key => $link)
     <div>
        <a class="btn btn-primary" href="{!! $link !!}">{!! $key  !!}</a>
        <span>{!! str_replace('//www', 'www', $link) !!}</span>
     </div>
    @endforeach
@endsection