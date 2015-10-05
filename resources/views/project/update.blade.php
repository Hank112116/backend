@extends('layouts.master')

@section('css')
    <link rel="stylesheet" href="/css/project-update.css">
@stop

@section('js')
    <script src='//maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places&language=en'></script>
    <script type="text/javascript" src="/react/owner-select.js"></script>
    <script src='/js/project-update.js'></script>
@stop

@section('content')
	<div class="page-header">
	    <h1>{{ $project->project_title }}</h1>

        <div>
            <a href="/project/update-status/draft/{!! $project->project_id !!}"
               class="btn-mini">
                <i class="fa fa-pencil-square-o fa-fw"></i>Change To Unfinished Draft
            </a>

            <a href="/project/update-status/private/{!! $project->project_id !!}"
               class="btn-mini btn-flat-green">
                <i class="fa fa-lock fa-fw"></i>Change To Private
            </a>

            <a href="/project/update-status/public/{!! $project->project_id !!}"
                class="btn-mini btn-flat-purple">
                <i class="fa fa-unlock-alt fa-fw"></i>Change To Public( Expert-Only )
            </a>

            @if(!$project->is_deleted)
            <a href="/project/delete/{!! $project->project_id !!}"
               class="btn-mini btn-flat-red js-delete">
                <i class="fa fa-trash-o fa-fw"></i>Delete
            </a>
            @endif
        </div>

	</div>

    <div id="origin" class="active form-container">
	   {!!
	        Form::open([
                'action'    => ['ProjectController@update', $project->project_id],
			    'method'    => 'POST',
			    'id'        => 'project-update-form',
			    'class'     => 'form-horizontal',
			    'role'      => 'form',
			    'enctype'   => 'multipart/form-data'
			])
	    !!}

            @include('project.update-common')
            @include('project.update-project-only')

            <div class="form-group">
                <div class="col-md-9 col-md-push-3">
                    <button class="btn-sassy btn-submit">UPDATE</button>
                </div>
            </div>

	   {!! Form::close() !!}		
    </div>

@stop

