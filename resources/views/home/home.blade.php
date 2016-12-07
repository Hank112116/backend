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
		<div class="panel panel-login">
			@if(session()->has('login_error_msg'))
			<div class="panel-heading">
				<div class="col-md-offset-0 login-error-msg">
					<div class="triangle"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i></div>
					<div class="message">{!! session()->get('login_error_msg') !!}</div>
				</div>
				<hr>
			</div>
			@endif
			<div class="panel-body">
				{!! Form::open(['url' => 'login', 'method' => 'POST', 'class' => 'login-form', 'id' => 'login-form']) !!}
					{!! Form::email('email', session()->get('login_oauth_email'), ['placeholder' => 'Email', 'class'=>'form-control']) !!}
					{!! Form::password('password', ['placeholder' => 'Password', 'class' => session()->get('login_password_error') ? 'password-error' : 'form-control']) !!}
					<button id="login-submit" class='btn-mini btn-sassy btn-flat-purple'>LOGIN</button>
				{!! Form::close() !!}
			</div>
		</div>
  	</div>
  	@endif
@stop

