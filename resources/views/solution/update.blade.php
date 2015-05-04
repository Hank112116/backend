@extends('layouts.master')

@section('css')
    @cssLoader('solution-update')
@stop

@section('js')
    <script src='//maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places&language=en'></script>
    <script type="text/javascript" src="/react/solution-update.js"></script>
    @jsLoader('solution-update')
@stop

@section('content')
	<div class="page-header">
	    <h1>{!! $solution->solution_title !!}</h1>

        <div>
            @if($solution->isOngoing() and !$is_restricted)
                @if($solution->isOffShelf())
                    <a href="{!! action('SolutionController@onShelf', $solution->solution_id) !!}"
                        class="btn-mini btn-flat-green">
                        <i class="fa fa-shield fa-fw"></i>On Shelf
                    </a>
                @else
                    <a href="{!! action('SolutionController@offShelf', $solution->solution_id) !!}"
                       class="btn-mini btn-flat-purple">
                        <i class="fa fa-shield fa-fw"></i>Off Shelf
                    </a>
                @endif
            @endif

            @if ($solution->isWaitApprove() and (!$solution->is_manager_approved or Auth::user()->isAdmin()))
            <a href="{!! action('SolutionController@approve', $solution->solution_id) !!}"
                class="btn-mini btn-flat-green">
                <i class="fa fa-shield fa-fw"></i> Approve
            </a>

            <a href="{!! action('SolutionController@reject', $solution->solution_id) !!}"
                class="btn-mini btn-flat-purple">
                <i class="fa fa-shield fa-fw"></i> Reject
            </a>
            @endif
        </div>

	</div>

    <div id="origin" class="active form-container">
	   {!! Form::open([
            'action' => ['SolutionController@update', $solution->solution_id],
			'method' => 'POST', 'class' => 'form-horizontal', 'role' => 'form',
			'enctype' => 'multipart/form-data' ]) !!}

            @include('solution.update-form')

            <div class="form-group">
                <div class="col-md-9 col-md-push-3">
                    <button class="btn-sassy btn-submit">Update</button>
                </div>
            </div>
	   {!! Form::close() !!}
    </div>

@stop

