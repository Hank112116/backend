@extends('layouts.master')

@section('css')
	<link rel="stylesheet" href="{{ LinkGen::assets('css/adminer-update.css') }}">
@stop

@section('js')
    <script type="text/javascript" src="{{ LinkGen::assets('react/owner-select.js') }}"></script>
@stop

@section('content')
<div class="page-header">
    <h1>ADD NEW MEMBER</h1>
</div>

<div class="form-container">
    {!! Form::open(['action' => 'AdminerController@create', 
                   'method' => 'POST', 'class' => 'form-horizontal', 'autocomplete'=>"off"]) !!}

    <div class="form-group">
        <label for="name" class="col-md-3">Name</label>

        <div class="col-md-5">
            {!! Form::text('name', '',
            ['placeholder' => 'Enter Name', 'class'=>'form-control', 'id' => 'name']) !!}
        </div>
        <div class="col-md-5 error">{!! $errors->first('name') !!}</div>
    </div>

    <div class="form-group">
        <label for="mail" class="col-md-3">Email</label>

        <div class="col-md-5">
            {!! Form::email('email', '',
            ['placeholder' => 'Enter Email', 'class'=>'form-control', 'id' => 'mail']) !!}
        </div>
        <div class="col-md-5 error">{!! $errors->first('email') !!}</div>
    </div>

    <div class="form-group">
        <label for="password" class="col-md-3">New Password</label>

        <div class="col-md-5">
            {!! Form::password('password', ['placeholder' => 'Enter New Password', 'class'=>'form-control', 'id' =>
            'password' ]) !!}
        </div>
        <div class="col-md-5 error">{!! $errors->first('password') !!}</div>
    </div>

    <div class="form-group">
        <label for="confirm-password" class="col-md-3">Confirm Password</label>

        <div class="col-md-5">
            {!! Form::password('password_confirmation',
            ['placeholder' => 'Confirm Password', 'class'=>'form-control', 'id' => 'confirm-password' ]) !!}
        </div>
        <div class="col-md-5 error"></div>
    </div>

    <div class="form-group">
        <label for="role" class="col-md-3">Role</label>

        <div class="col-md-5">
            <select class="form-control" id="role" name="role_id">
                @foreach($roles_options as $role_id => $role_option)
                    <option value="{!! $role_id !!}" >
                        {!! $role_option !!}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-5 error"></div>
    </div>

    <!-- Front Member -->
    <div class="form-group">
        <label for="member" class="col-md-3">HWTrek Member</label>
         <div id="owner-selector" class="col-md-5" data-user = ''></div>
    </div>

    <div class="form-group">
        <label for="" class="col-md-3"></label>

        <div class="col-md-9">
            <button class="btn-sassy btn-submit">CREATE</button>
        </div>
    </div>
    {!! Form::close() !!}
</div>

@stop

