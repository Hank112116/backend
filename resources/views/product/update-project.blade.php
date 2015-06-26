@extends('layouts.master')

@section('css')
    <link rel="stylesheet" href="/css/project-update.css">
@stop

@section('js')
    <script src='/js/project-only-update.js'></script>
@stop

@section('content')
	<div class="page-header">
	    <h1>{!! $project->project_title !!}</h1>
	</div>

    <div id="origin" class="active form-container">
	   {!!
	        Form::open([
                'action'    => ['ProductController@updateProject', $project->project_id],
			    'method'    => 'POST',
			    'id'        => 'project-update-form',
			    'class'     => 'form-horizontal',
			    'role'      => 'form',
			    'enctype'   => 'multipart/form-data'
			])
	    !!}

            <!-- Project Cover Art -->
            <div class="form-group">
                <label for="cover" class="col-md-3">Project Cover Art</label>
                <div class="col-md-5">
                    {!! HTML::image($project->getImagePath(), '', ['class' => 'cover']) !!}
                </div>
            </div>

            <!-- Project Owner -->
            <div class="form-group">
                <label for="member" class="col-md-3">Project Owner</label>
                <div class="col-md-5">{!! $project->textUserName() !!}</div>
            </div>

            <!-- Project Category -->
            <div class="form-group">
                <label for="category" class="col-md-3">Project Category</label>
                <div class="col-md-5">{!! $project->textCategory() !!}</div>
            </div>

            <!-- Project Name -->
            <div class="form-group">
                <label for="title" class="col-md-3">Project Name</label>
                <div class="col-md-5">{!! $project->project_title !!}</div>
            </div>

            @include('project.update-project-only')

            <div class="form-group">
                <div class="col-md-9 col-md-push-3">
                    <button class="btn-sassy btn-submit">UPDATE</button>
                </div>
            </div>

	   {!! Form::close() !!}		
    </div>

@stop

