@extends('layouts.master')

@section('css')
    <link rel="stylesheet" href="/css/solution-update.css">
@stop

@section('js')
    <script src='//maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places&language=en'></script>
    <script type="text/javascript" src="/react/solution-gallery.js"></script>
    <script type="text/javascript" src="/react/owner-select.js"></script>
    <script src='/js/solution-update.js'></script>
@stop

@section('content')
<div class="page-header">
    <h1>{!! $solution->solution_title !!}</h1>

    <div>
        @if( !$solution->is_manager_approved or Auth::user()->isAdmin() )
        <a href="{!! action('SolutionController@approveEdition', $solution->solution_id) !!}"
            class="btn-mini btn-flat-green">
            <i class="fa fa-shield fa-fw"></i> Approve <i class="fa fa-copy"></i>
        </a>

        <a href="{!! action('SolutionController@rejectEdition', $solution->solution_id) !!}"
            class="btn-mini btn-flat-purple">
            <i class="fa fa-shield fa-fw"></i> Reject <i class="fa fa-copy"></i>
        </a>
         @endif
    </div>
</div>

<div id="to-approve" class="form-container">
    {!! Form::open([
    'action' => ['SolutionController@updateOngoing', $solution->solution_id],
    'method' => 'POST', 'class' => 'form-horizontal', 'role' => 'form',
    'enctype' => 'multipart/form-data' ]) !!}

    @include('solution.update-form')

    <div class="form-group">
        <div class="col-md-9 col-md-push-3">
            <button class="btn-sassy btn-submit">
                UPDATE <i class="fa fa-copy"></i>
            </button>
        </div>
    </div>
    {!! Form::close() !!}
</div>
@stop

