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

        @if (!$is_restricted)

        <!-- Active -->
        <div class="form-group">
            <label for="verify" class="col-md-3">
                EMail Verify<br/>
                <span class="color-info">{!! $user->textEmailVerify() !!}</span>
            </label>

            <div class="col-md-5">
                <div>
                    {!! Form::radio('email_verify', Backend\Model\Eloquent\User::EMAIL_VERIFY, $user->verified()) !!}
                    <label for="active_1" class='iradio-lable'>Verify</label>
                </div>

                <div>
                    {!! Form::radio('email_verify', Backend\Model\Eloquent\User::EMAIL_VERIFY_NONE, !$user->verified()) !!}
                    <label for="active_0" class='iradio-lable'>None</label>
                </div>
            </div>

            <div class="col-md-5"></div>
        </div>

        <!-- Active -->
        <div class="form-group">
            <label for="active" class="col-md-3">Active</label>
            <div class="col-md-5">
                <div>
                    {!! Form::radio('active', '1', $user->active==1, ["id"=>"active_1"]) !!}
                    <label for="active_1" class='iradio-lable'>Active</label>
                </div>

                <div>
                    {!! Form::radio('active', '0', $user->active==0, ["id"=>"active_0"]) !!}
                    <label for="active_0" class='iradio-lable'>Suspend</label>
                </div>
            </div>
            <div class="col-md-5"></div>
        </div>

        <!-- Expert / Customer -->
        <div class="form-group">
            <label for="role" class="col-md-3">
                Type
                @if($user->isToBeExpert())
                <br/>
                <span class="color-info">
                    <i class="fa fa-exclamation-triangle fa-fw"></i>
                    Pending to be expert
                </span>
                @endif
            </label>

            <div class="col-md-5">
                <div>
                    {!! Form::radio('user_type', '0', $user->user_type==0, ["id"=>"user_type_0"]) !!}
                    <label for="user_type_0" class='iradio-lable'>Creator</label>
                </div>

                <div>
                    {!! Form::radio('user_type', '1', $user->user_type==1, ["id"=>"user_type_1"]) !!}
                    <label for="user_type_1" class='iradio-lable'>Expert</label>
                </div>
            </div>
        </div>
        @endif

        <div class="form-group">
            <label for="email" class="col-md-3">EMail</label>
            <div class="col-md-5">
                @if($is_restricted)
                    {!! $user->email !!}
                    {!! Form::hidden('email', $user->email) !!}
                @else
                    {!! Form::email(
                        'email', $user->email,
                        ['placeholder' => 'Enter EMail', 'class'=>'form-control', 'id' => 'email']) !!}
                @endif
            </div>
            <div class="col-md-5"><span class='error'>{!! $errors->first('email') !!}</span></div>

        </div>


        <div class="form-group">
            <label for="first-name" class="col-md-3">First Name</label>
            <div class="col-md-5">
                @if($is_restricted)
                    {!! $user->user_name !!}
                @else
                    {!! Form::text(
                        'user_name', htmlspecialchars($user->user_name),
                        ['placeholder' => 'Enter First Name', 'class'=>'form-control', 'id' => 'first-name']) !!}
                @endif

            </div>
            <div class="col-md-5"></div>
        </div>

        <div class="form-group">
            <label for="last-name" class="col-md-3">Last Name</label>
            <div class="col-md-5">
                @if($is_restricted)
                    {!! $user->last_name !!}
                @else
                    {!! Form::text('last_name', htmlspecialchars($user->last_name),
                    ['placeholder' => 'Enter Last Name', 'class'=>'form-control', 'id'=>'last-name']) !!}
                @endif
            </div>
            <div class="col-md-5"></div>
        </div>
        
        @if (!$is_restricted)
        {{--
        <div class="form-group">
            <label for="role" class="col-md-3">Role</label>
            <div class="col-md-5">                
                {!! Form::radio('user_role', '0', $user->user_role==0, ["id"=>"user_role_0"]) !!}
                <label for="user_role_0" class='iradio-lable'>Member</label>
                
                {!! Form::radio('user_role', '1', $user->user_role==1, ["id"=>"user_role_1"]) !!}
                <label for="user_role_1" class='iradio-lable'>Manager</label>

                {!! Form::radio('user_role', '2', $user->user_role==2, ["id"=>"user_role_2"]) !!}
                <label for="user_role_2" class='iradio-lable'>Admin</label>
            </div>
            <div class="col-md-5"></div>
        </div>
        --}}

        <div class="form-group">
            <label for="country" class="col-md-3">Address</label>
            <div class="col-md-5">
                {!! $user->address !!}
            </div>
            <div class="col-md-5"></div>
        </div>

        <div class="form-group">
            <label for="country" class="col-md-3">Country</label>
            <div class="col-md-5">
                {!! Form::text('country', $user->country,
                    ['placeholder' => 'Country', 'class'=>'form-control', 'id'=>'country']) !!}
            </div>
            <div class="col-md-5"></div>
        </div>

        <div class="form-group">
            <label for="city" class="col-md-3">City</label>
            <div class="col-md-5">
                {!! Form::text('city', $user->city,
                    ['placeholder' => 'City', 'class'=>'form-control', 'id'=>'city']) !!}
            </div>
            <div class="col-md-5"></div>
        </div>
        @endif

        @if ($user->isExpert() or $user->isToBeExpert())
            <div class="form-group">
                <label for="company" class="col-md-3">Company</label>
                <div class="col-md-5">
                    {!! Form::text('company', $user->company,
                        ['placeholder' => 'Company', 'class'=>'form-control', 'id'=>'company']) !!}
                </div>
                <div class="col-md-5"></div>
            </div>
        
            @if (!$is_restricted)
            <div class="form-group">
                <label for="company-url" class="col-md-3">Company URL</label>
                <div class="col-md-5">
                    {!! Form::text('company_url', $user->company_url,
                        ['placeholder' => 'Company URL', 'class'=>'form-control', 'id'=>'company-url']) !!}
                </div>
                <div class="col-md-5"></div>
            </div>

            <div class="form-group">
                <label for="personal-url" class="col-md-3">Personal URL</label>
                <div class="col-md-5">
                    {!! Form::text('personal_url', $user->personal_url,
                        ['placeholder' => 'Personal URL', 'class'=>'form-control', 'id'=>'personal-url']) !!}
                </div>
                <div class="col-md-5"></div>
            </div>
        @endif

        <div class="form-group">
            <label for="position" class="col-md-3">Position</label>
            <div class="col-md-5">
                {!! Form::text('business_id', $user->business_id,
                    ['placeholder' => 'Position', 'class'=>'form-control', 'id'=>'position']) !!}
            </div>
            <div class="col-md-5"></div>
        </div>

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

