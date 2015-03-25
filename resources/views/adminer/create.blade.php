@extends('layouts.master')

@section('css')
	<link rel="stylesheet" href="/css/adminer-update.css">
@stop

@section('js')
  
@stop

@section('content')
<div class="page-header">
    <h1>ADD NEW MEMBER</h1>
</div>

<div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3">
    {!! Form::open(['action' => 'AdminerController@create', 
                   'method' => 'POST', 'class' => 'update-form', 'autocomplete'=>"off"]) !!}

    <div class="form-group">
        <label for="name">Name</label>
        {!! Form::text('name', '',
            ['placeholder' => 'Member name', 'class'=>'form-control', 'id' => 'name' ]) !!}
         
         <p class='error'>{!!  $errors->first('name') !!}</p>
    </div>  

    <div class="form-group">
        <label for="mail">Email</label>
        {!! Form::email('email', '',
            ['placeholder' => 'Email', 'class'=>'form-control', 'id' => 'mail', "autocomplete"=>"off" ]) !!}
        
        <p class='error'>{!!  $errors->first('email') !!}</p>
    </div>  

    <div class="form-group">
        <label for="password">Password</label>
        {!! Form::password('password', 
            ['placeholder' => 'Password', 'class'=>'form-control', 'id' => 'password', "autocomplete"=>"off"]) !!}
       
        <p class='error'>{!!  $errors->first('password') !!}</p>
    </div>  

    <div class="form-group">
        <label for="confirm-password">Confirm Password</label>
        {!! Form::password('password_confirmation', 
                ['placeholder' => 'Confirm Password', 'class'=>'form-control', 'id' => 'confirm-password' ]) !!}
    </div>  

    <div class="form-group">
        <label for="role">Role</label>
        <select class="form-control" id="role" name="role_id">
            @foreach($roles_options as $role_id => $role_option)
                <option value="{!! $role_id !!}">{!! $role_option !!}</option>
            @endforeach
        </select>
    </div>  

    <button class='btn-sassy'>CREATE</button>
    {!! Form::close() !!}
</div>

@stop

