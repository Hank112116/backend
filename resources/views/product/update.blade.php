@extends('layouts.master')

@section('css')
    <link rel="stylesheet" href="/css/product-update.css">
@stop

@section('js')
    <script src='//maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places&language=en'></script>
    <script type="text/javascript" src="/react/owner-select.js"></script>
@stop

@section('content')
	<div class="page-header">
	    <h1>{{ $project->project_title }}</h1>
        <div>
            @if($project->is_project_submitted)
                {!! link_to_action('ProductController@showProjectUpdate', 'Project Update',
                        $project->project_id, ['class' => 'btn-mini']) !!}
            @endif

            @if($project->profile->is_ongoing and !$project->profile->is_postpone)
                {!! link_to_action(
                    'ProductController@postpone', 'POSTPONE',
                     $project->project_id, ['class' => 'btn-mini']) !!}
            @elseif($project->profile->is_postpone)
                {!! link_to_action(
                    'ProductController@recoverPostpone', 'RECOVER POSTPONE',
                     $project->project_id, ['class' => 'btn-mini']) !!}
            @endif

            @if ($project->profile->is_wait_approve_product)
            <a href="/product/approve/{!! $project->project_id !!}"
               class="btn-mini btn-flat-green">
                <i class="fa fa-pencil-square-o fa-fw"></i>APPROVE
            </a>

            <a href="/product/reject/{!! $project->project_id !!}"
               class="btn-mini btn-flat-purple">
                <i class="fa fa-pencil-square-o fa-fw"></i>REJECT
            </a>
            @endif
        </div>
	</div>

    <div id="origin" class="active form-container">
	   {!!
	        Form::open([
                'action' => ['ProductController@update', $project->project_id],
			    'method' => 'POST', 'id' => 'project-update-form',
			    'class' => 'form-horizontal', 'role' => 'form',
			    'enctype' => 'multipart/form-data'
			])
	    !!}
            @include('project.update-common')
            @include('product.update-product-only')

            <div class="form-group">
                <div class="col-md-9 col-md-push-3">
                    <button class="btn-sassy btn-submit">UPDATE</button>
                </div>
            </div>
	   {!! Form::close() !!}		
    </div>

@stop

