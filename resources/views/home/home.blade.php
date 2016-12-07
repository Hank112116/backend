@extends('layouts.master')

@section('css')
    <link rel="stylesheet" href="{{ LinkGen::assets('css/home.css') }}">
	<link rel="stylesheet" href="{{ LinkGen::assets('css/login.css') }}">
@stop

@section('js')
	<script src="{{ LinkGen::assets('js/login.js') }}"></script>
@stop

@section('content')
	<div class="page-header">
	  <h1>HWTrek Back-end System</h1>
	</div>

	@if(!auth()->check())
  	<div class="info">
		<div class="panel panel-login">
			<div class="panel-heading">
				<div class="row">
					<div class="col-xs-6">
						<a href="#" class="active" id="login-form-link">Login</a>
					</div>
					<div class="col-xs-6">
						<a href="#" id="oauth-login-form-link">OAuth Login</a>
					</div>
				</div>
				<hr>
			</div>
			<div class="panel-body">
				{!! Form::open(['url' => 'login', 'method' => 'POST', 'class' => 'login-form', 'id' => 'login-form']) !!}
					{!! Form::email('email', '',['placeholder' => 'Backend Email', 'class'=>'form-control']) !!}
					{!! Form::password('password', ['placeholder' => 'Backend Password', 'class' => 'form-control']) !!}
					<button id="login-submit" class='btn-sassy'>LOGIN</button>
				{!! Form::close() !!}

				{!! Form::open(['url' => 'oauth-login', 'method' => 'POST', 'class' => 'login-form', 'id' => 'oauth-form']) !!}
					{!! Form::email('email', '',['placeholder' => 'HWTrek Email', 'class'=>'form-control']) !!}
					{!! Form::password('password', ['placeholder' => 'HWTrek Password', 'class' => 'form-control', 'pattern' => '.{8,}', 'required title' => 'Please use at least 8 characters for your password']) !!}
					<button id="login-submit" class='btn-sassy'>LOGIN</button>
				{!! Form::close() !!}
			</div>
		</div>
  	</div>
  	@endif
@stop

