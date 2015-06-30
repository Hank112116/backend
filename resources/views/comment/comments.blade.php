@extends('layouts.master')

@section('css')
    <link rel="stylesheet" href="/css/comments.css">    
@stop

@section('js')
    <script type="text/javascript" src="/react/comment.js"></script>
@stop

@section('content')
	<div id="comment-image-view"></div>

	<div class="page-header">
	  <h1>{!! $title !!} Comments</h1>
	</div>

	<div id="comments" class="comments" data-type="{!! $type !!}"></div>
@stop

