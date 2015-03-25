@extends('layouts.master')
@include('layouts.macro')

@section('css')
	<link rel="stylesheet" href="/css/mail-template-detail.css">
    <style>
        td.wrapper {
          background: transparent;
        }
    </style>
@stop

@section('js')
 {{-- <script src='/js/mail-template-detail.js'></script> --}}
@stop

@section('content')

<div class="page-header">
    <h1>EMAIL TEMPLATE DETAIL</h1>
</div>

<div id="user-detail">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">

			<div class="data-group">
			  <span class="label">Task</span>
			  <span class="content">{!! $email->task !!}</span>
			</div>

			<div class="data-group">
			  <span class="label">From Address</span>
			  <span class="content">{!! $email->from_address !!}</span>
			</div>

			<div class="data-group">
			  <span class="label">Reply Address</span>
			  <span class="content">{!! $email->reply_address !!}</span>
			</div>

			<div class="data-group">
			  <span class="label">Subject</span>
			  <span class="content">{!! $email->subject !!}</span>
			</div>

		</div>
	</div>
	
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="panel panel-default">
			  	<div class="panel-heading">Message</div>
			  	<div class="panel-body js-message">
                    {{-- nl2br(e($email->message)) --}}

                    {!!  str_replace('{content}', str_replace('{break}', '<br/>', $email->message), $template) !!}
                </div>
			</div>			
		</div>
	</div>

</div>

@stop

