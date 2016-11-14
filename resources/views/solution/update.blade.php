@extends('layouts.master')

@section('css')
    <link rel="stylesheet" href="{{ LinkGen::assets('css/solution-update.css') }}">
@stop

@section('js')
    <script src='//maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places&language=en'></script>
    <script src="{{ LinkGen::assets('react/solution-update.js') }}"></script>
    <script src="{{ LinkGen::assets('js/solution-update.js') }}"></script>
@stop

@section('content')
	<div class="page-header">
	    <h1>{{ $solution->solution_title }}</h1>

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
            @if (!$is_restricted && $solution->isWaitApprove() and (!$solution->is_manager_approved or auth()->user()->isAdmin() or auth()->user()->isManagerHead()))
            <button class="btn-mini btn-flat-green js-approve-solution" rel="{{ $solution->solution_id }}">
                <i class="fa fa-shield fa-fw"></i> Approve</button>
            <button class="btn-mini btn-flat-purple js-reject-solution" rel="{{ $solution->solution_id }}">
                <i class="fa fa-shield fa-fw"></i> Reject</button>
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

