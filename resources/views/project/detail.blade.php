@extends('layouts.master')
@include('layouts.macro')

@section('css')
    <link rel="stylesheet" href="{{ LinkGen::assets('css/project-detail.css') }}">
@stop

@section('content')

<div class="page-header">
    <h1>{{ $project->project_title }}</h1>

    <div>
        <a href="{!! $project->textFrontLink() !!}" target="_blank" class="btn-mini">
            <i class="fa fa-eye"></i> Project Page
        </a>

        {!!
            link_to_action('ProjectController@showUpdate', 'EDIT',
                $project->project_id, ['class' => 'btn-mini'])
        !!}
    </div>
</div>

@include('project.detail-column')

@stop

