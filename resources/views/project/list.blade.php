@php
/* @var \Backend\Model\Project\Entity\BasicProject $project */
@endphp
@extends('layouts.master')
@include('layouts.macro')
@section('jqui')
    @include('layouts.jqui')
@stop
@section('css')
    <link rel="stylesheet" href="{{ LinkGen::assets('css/project-list.css') }}">
@stop

@section('js')
    <script src="{{ LinkGen::assets('js/list.js') }}"></script>
    <script src="{{ LinkGen::assets('js/project-list.js') }}"></script>
@stop

@section('content')

<div class="page-header">
    <h1>Projects</h1>

    <div id="output_all" class='header-output'>
        @if(auth()->user()->isAdmin())
            {!! link_to_action('ProjectController@csv', 'CSV', [], [
                'target' => '_blank',
                'class'  => 'btn-mini header-output-link'
            ]) !!}
        @endif
    </div>
</div>

<div class="search-bar">
    @include ('project.list-search')
</div>

<div class="row">
    <div class="col-md-12">
        <table class="table table-striped float-thead">
            <thead>
            <tr>
                <th>#</th>
                <th class="table--name">Project Title<br/><span class="table--text-light">Category</span></th>
                <th>Owner<br/><span class="table--text-light">Country</span></th>
                <th>Status<br/><span class="table--text-light">Status Change</span></th>
                <th>Company</th>
                <th>Assigned PM<br/><span class="table--text-light">Email Out</span></th>
                <th>Statistics</th>
                <th>Internal description</th>
                <th>Action</th>
                <th>Feature tags<br/><span class="table--text-light">Internal tags</span></th>
                <th>Last Update<br/><span class="table--text-light">By</span></th>
                <th></th>
            </tr>
            </thead>

            <tbody>
            @foreach($projects as $project)
                <tr id="row-{{ $project->getId() }}">
                @include('project.row', ['project' => $project])
                </tr>
            @endforeach
            </tbody>
        </table>

    </div>
    @include('project.dialog.schedule-grade-dialog')
    @include('project.dialog.email-recommend-expert-dialog')
    @include('project.dialog.add-tags-dialog')
    @include('project.dialog.description-dialog')
    @include('project.dialog.schedule-manager-dialog')
    @include('report.dialog.project-report-action')
</div>

<input type="hidden" id="route-path" value="{{ Route::current()->uri() }}">
<div class="text-center">
    {!! $projects->appends(request()->all())->render() !!}
</div>
@stop

