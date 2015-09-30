@extends('layouts.master')
@include('layouts.macro')

@section('css')
	<link rel="stylesheet" href="/css/user-detail.css">
@stop

@section('js')
	<script src='/js/user-detail.js'></script>
@stop

@section('content')

<div class="page-header">
    <h1>MEMBER DETAIL</h1>
    <a href="{!! $user->textFrontLink() !!}" target="_blank" class="btn-mini">
    	<i class="fa fa-eye"></i>Front
    </a>
    {!! link_to_action(
        'UserController@showUpdate', 'EDIT', 
         $user->user_id, ['class' => 'btn-mini']) !!}
</div>

<div id="user-detail">
	<div class="row">
		<div class="col-md-2 col-md-offset-1">
        	{!! HTML::image($user->getImagePath(), 'member-thumb', ['class' => 'user-avatar']) !!}
		</div>

		<div class="col-md-7 clearfix">
			<div class="data-group">
			  <span class="label">Name</span>
			  <span class="content">{{ $user->textFullName() }}</span>
			</div>

			<div class="data-group">
			  <span class="label">EMail</span>
			  <span class="content">
				  {{ $user->email }}
				  @if('facebook' === $user->social)
					  <i class="fa fa-facebook-square"></i>
				  @elseif('linkedin' === $user->social)
					  <i class="fa fa-linkedin-square"></i>
				  @endif
			  </span>
			</div>

			<div class="data-group group-half">
			  <span class="label">Type</span>
			  <span class="content">{!! $user->textType() !!}</span>
			</div>

			@if (!$is_restricted)
			<div class="data-group group-half">
			  <span class="label">Active</span>
			  <span class="content">{!! $user->textActive() !!} ( EMail : {!! $user->textEmailVerify() !!} )</span>
			</div>
			@endif
		</div>		
	</div> 

	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<div class="data-group">
			  <span class="label">Country</span>
			  <span class="content">{{ $user->country }}</span>
			</div>

			<div class="data-group">
			  <span class="label">City</span>
			  <span class="content">{{ $user->city }}</span>
			</div>

			<div class="data-group">
			  <span class="label">Address</span>
			  <span class="content">{{ $user->address }}</span>
			</div>
			
			@if (!$is_restricted)
			<div class="data-group">
			  <span class="label">Paypal EMail</span>
			  <span class="content">{{ $user->paypal_mail }}</span>
			</div>
			@endif
			
			@if ($user->isExpert() or $user->isToBeExpert())
				@include ('user.detail-expert-company') 
			@endif
			
			<div class="clearfix">
				<div class="data-group group-half">
				  <span class="label">Registed On</span>
				  <span class="content">{!! $user->date_added !!}</span>
				</div>	
				@if (!$is_restricted)
				<div class="data-group group-half">
				  <span class="label">Signup Ip</span>
				  <span class="content">{!! $user->signup_ip !!}</span>
				</div>		
				@endif	
			</div>
		</div>
	</div>
	
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<div class="panel panel-default">
			  <div class="panel-heading">Biography</div>
			  <div class="panel-body">
			    {!! Purifier::clean($user->user_about) !!}
			  </div>
			</div>			
		</div>
	</div>

	@if ($user->isExpert() or $user->isToBeExpert())
		<div class="row">
			<div class="col-md-10 col-md-offset-1">
				<div class="panel panel-default">
				  	<div class="panel-heading">Industry</div>
				  	<div class="panel-body">
			  			@foreach($user->getIndustryArray() as $tag)
			  			<span class='tag'>{!! $tag !!}</span>
			  			@endforeach			    
				  	</div>
				</div>			
			</div>
		</div>
		@include ('modules.detail-expertise', ['title' => 'Expertise tags'])
	@endif

	@if(!$is_restricted)
		@include ('user.detail-projects')
		@include ('user.detail-products')
	@endif

	@include ('user.detail-solutions')
    {{-- @include ('user.detail-backed-products') --}}
</div>

@stop

