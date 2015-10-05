@extends('layouts.master')

@section('css')
    <link rel="stylesheet" href="/css/user-update.css">
@stop

@section('js')
    <script src='//maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places&language=en'></script>
    <script src='/js/user-update.js'></script>
@stop

@section('content')
    <div class="page-header">
        <h1>{{ $user->textFullName() }}</h1>
    </div>

    <div class="form-container">
        {!! Form::open([ 'action' => ['UserController@update', $user->user_id],
                        'method' => 'POST', 'class' => 'form-horizontal', 'role' => 'form',
                        'enctype' => 'multipart/form-data' ]) !!}

        <div class="form-group">

            <label for="user-head" class="col-md-3">Upload Image</label>
            <div class="col-md-5">
                {!! HTML::image($user->getImagePath(), 'thumb', ['class' => 'user-avatar']) !!}
                <input type="file" name="head" id="user-head">
            </div>
            <div class="col-md-5"></div>
        </div>

        <!-- Active -->
        <div class="form-group">
            <label for="verify" class="col-md-3">
                EMail Verify<br/>
            </label>

            <div class="col-md-5">
                <div>
                    @if($user->verified())
                    Verify
                    @else
                    None
                    @endif
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="role" class="col-md-3">
                Type
            </label>
            <div class="col-md-5">
                {!! ($user->isToBeExpert() && $user->isCreator())?'<font color="red">Pending to Be Expert</font>':$user->textType()  !!}
            </div>
        </div>

        {!! Form::hidden('user_type', $user->user_type) !!}
        {!! Form::hidden('email', $user->email) !!}

        <div class="form-group">
            <label for="first-name" class="col-md-3">First Name</label>
            <div class="col-md-5">
                {{ $user->user_name }}
            </div>
            <div class="col-md-5"></div>
        </div>

        <div class="form-group">
            <label for="last-name" class="col-md-3">Last Name</label>
            <div class="col-md-5">
                {{ $user->last_name }}
            </div>
            <div class="col-md-5"></div>
        </div>

        @if ($user->isExpert() or $user->isToBeExpert())
            <div class="form-group">
                <label for="industry" class="col-md-3">Industry</label>
                <div class="col-md-9 industry">
                    @include('user.update-industry')
                </div>
            </div>
        @endif

        <div class="form-group">
            <label for="biography" class="col-md-3">Biography</label>
            <div class="col-md-9">
                {!! Form::textarea('user_about', Purifier::clean($user->user_about),
                    ['placeholder' => 'Biography', 'class'=>'js-editor', 'id'=>'biography']) !!}
            </div>
        </div>

        @if ($user->isExpert() or $user->isToBeExpert())
            <div class="form-group">
                <label for="experties" class="col-md-3">Expertise Tags</label>
                <div class="col-md-9 expertise" data-select-tags="expertises">
                    @include('modules.update-expertise')
                </div>
            </div>
        @endif

        <div class="form-group">
            <label for="" class="col-md-3"></label>
            <div class="col-md-9">
                <button class="btn-sassy btn-submit">Update</button>
            </div>
        </div>    　
        {!! Form::close() !!}
    </div>

@stop

