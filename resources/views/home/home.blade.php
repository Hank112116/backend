@extends('layouts.master')

@section('css')
    <link rel="stylesheet" href="/css/home.css">
@stop

@section('content')

	<div class="page-header">
	  <h1>HWTrek Back-end System</h1>
	</div>

	@if(!Auth::check())
  	<div class="info">
	  	{!! Form::open(['url' => 'login', 'method' => 'POST', 'class' => 'login-form']) !!}
	  		{!! Form::email('email', '',['placeholder' => 'email', 'class'=>'form-control']) !!}
	  		{!! Form::password('password', ['placeholder' => 'Password', 'class' => 'form-control']) !!}
			<button id="login-submit" class='btn-sassy'>LOGIN</button>
	  	{!! Form::close() !!}
  	</div>
  	@endif
@stop

