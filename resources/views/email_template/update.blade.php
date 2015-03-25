@extends('layouts.master')

@section('css')
	<link rel="stylesheet" href="/css/mail-template-update.css">
@stop

@section('js')
    <script type="text/javascript" src="/react/email-template-editor.js"></script>
@stop

@section('content')

<div class="page-header">
    <h1>EDIT EMAIL TEMPLATE</h1>
</div>

<div class="form-container">
   {!! Form::open([ 
        'action' => ['MailTemplateController@update', $email->email_template_id], 
        'method' => 'POST', 'class' => 'form-horizontal', 'role' => 'form']) !!}

        <div class="form-group">
            <label for="title" class="col-md-2">Task</label>
            <div class="col-md-5">
                <input type="text" class="form-control" id="title" 
                    name="task" value="{!! $email->task !!}">
            </div>
            <div class="col-md-5"></div>
        </div>

        <div class="form-group">
            <label for="from" class="col-md-2">From</label>
            <div class="col-md-5">
                <input type="text" class="form-control" id="from" 
                    name="from_address" value="{!! $email->from_address !!}">
            </div>
            <div class="col-md-5"></div>
        </div>

        <div class="form-group">
            <label for="reply" class="col-md-2">Reply</label>
            <div class="col-md-5">
                <input type="text" class="form-control" id="reply" 
                    name="reply_address" value="{!! $email->reply_address !!}">
            </div>
            <div class="col-md-5"></div>
        </div>

        <div class="form-group">
            <label for="subject" class="col-md-2">Subject</label>
            <div class="col-md-5">
                <input type="text" class="form-control" id="subject" 
                    name="subject" value="{!! $email->subject !!}">
            </div>
            <div class="col-md-5"></div>
        </div>

        <div class="form-group">
            <label for="message" class="col-md-2">Message</label>
            <div id="email-template-editor" class="col-md-10">
                {!! $email->message !!}
            </div>
        </div>

        <div class="form-group">
            <label for="var" class="col-md-2">Variables</label>
            <div class="col-md-10">
                <p class="tag-desc">Variables used in email templates</p>
                @foreach ($tags as $tag)
                    <span class="tag">{!! $tag !!}</span>
                @endforeach
            </div>
        </div>

        <div class="form-group">
            <div class="col-md-9 col-md-offset-2">
                <button class="btn-sassy btn-submit">UPDATE</button>
            </div>
        </div>
   {!! Form::close() !!}      
</div>

@stop

