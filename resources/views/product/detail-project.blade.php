@extends('layouts.master')
@include('layouts.macro')

@section('css')
	@cssLoader('product-detail')
@stop

@section('content')

<div class="page-header">
    <h1>{{ $project->project_title }}</h1>

    <div>
        <a href="{!! $project->textFrontProjectLink() !!}" target="_blank" class="btn-mini">
            <i class="fa fa-eye"></i> Project Page
        </a>

        {{
            link_to_action('ProductController@showProjectUpdate', 'EDIT',
                $project->project_id, ['class' => 'btn-mini'])
        !!}
    </div>
</div>

@include('project.detail-column')

@stop

