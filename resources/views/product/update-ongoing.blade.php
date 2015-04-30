@extends('layouts.master')

@section('css')
	@cssLoader('project-update')
@stop

@section('js')
    <script src='//maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places&language=en'></script>
    @jsLoader('project-update')
@stop

@section('content')
	<div class="page-header">
	    <h1><i class="fa fa-copy"></i>PRPDUCT EDIT</h1>
	</div>
            
    <div id="to-approve" class="form-container">
       {!! Form::open([ 
            'action' => ['ProductController@updateOngoing', $project->project_id],
            'method' => 'POST', 'class' => 'form-horizontal', 'role' => 'form',
            'enctype' => 'multipart/form-data' ]) !!}

            <div class="form-group">
                <div class="col-md-9 col-md-push-3">
                    <button class="btn-sassy btn-submit js-btn-approve">APPROVE</button>
                    <button class="btn-sassy btn-submit js-btn-reject">REJECT</button>
                </div>
            </div>

            @include('product.update-form')

            <div class="form-group">
                <div class="col-md-9 col-md-push-3">
                    <button class="btn-sassy btn-submit"><i class="fa fa-copy"></i>UPDATE</button>
                </div>
            </div>
       {!! Form::close() !!}              
    </div>
@stop

