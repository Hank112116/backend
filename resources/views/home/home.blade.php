@extends('layouts.master')

@section('css')
    <link rel="stylesheet" href="{{ LinkGen::assets('css/home.css') }}">
	<link rel="stylesheet" href="{{ LinkGen::assets('css/login.css') }}">
@stop

@section('content')
	<div class="page-header">
		<h2>Welcome to HWTrek Backend System</h2>
	</div>

	@if(!auth()->check())
  	<div class="info">
		<div class="panel-heading">
			<div class="row">
				<div class="col-xs-6">
					<span class="active">Login</span>
				</div>
			</div>
			@if(session()->has('login_error_msg'))
			<div class="row">
				<div class="col-xs-12 login-error-msg">
					<div class="triangle"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i></div>
					<div class="message">{!! session()->get('login_error_msg') !!}</div>
				</div>
			</div>
			@endif
			<hr>
		</div>

		<div class="panel-body">
			{!! Form::open(['url' => 'login', 'method' => 'POST', 'class' => 'login-form', 'id' => 'login-form']) !!}
				{!! Form::email('email', session()->get('login_oauth_email'), ['placeholder' => 'Enter your account email', 'class'=>'form-control']) !!}
				{!! Form::password('password', ['placeholder' => 'Enter your password', 'class' => session()->get('login_password_error') ? 'password-error' : 'form-control']) !!}
				<button id="login-submit" class='btn-mini btn-sassy btn-flat-purple'>LOGIN</button>
			{!! Form::close() !!}
		</div>
	</div>
  	@endif
@stop
