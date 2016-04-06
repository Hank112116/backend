@extends('layouts.master')
@include('layouts.macro')
@section('jqui')
    @include('layouts.jqui')
@stop
@section('css')
    <link rel="stylesheet" href="/css/product-list.css">
@stop

@section('js')
    <script src='/js/list.js'></script>
    <script src='/js/project-list.js'></script>
@stop

@section('content')

<div class="page-header">
    <h1>{!! $title !!}</h1>

    <div id="output_all" class='header-output'>
        @include('layouts.get-csv')
    </div>
</div>

<div class="search-bar">
    @include ('project.list-search')
</div>

<div class="row">
    <div class="col-md-12">
        <table class="table table-striped">
            <tr>
                <th>#</th>
                <th class="table--name">Project Title<br/><span class="table--text-light">Category</span></th>
                <th>Owner<br/><span class="table--text-light">Country</span></th>
                <th>Status<br/><span class="table--text-light">Status Change</span></th>
                <th>Company</th>
                <th>Assigned PM<br/><span class="table--text-light">Email Out</span></th>
                <th>Proposed</th>
                <th>Referrals</th>
                <th>Statistics</th>
                <th>Feature tags<br/><span class="table--text-light">Internal tags</span></th>
                <th>Internal description</th>
                <th>Last Update<br/><span class="table--text-light">By</span></th>
                <th></th>
            </tr>

            @foreach($projects as $project)
            <tr>
                <td>{!! $project->project_id !!}</td>
                <td class="table--name">
                    @if($project->is_deleted)
                        {{ $project->textTitle() }}
                    @else
                        <a href="{!! $project->textFrontLink() !!}" target="_blank">
                            {{ $project->textTitle() }}
                        </a>
                    @endif
                    <br/>
                    @include('modules.list-category', ['category' => $project->categoryData()])
                    @if($project->is_created_via_fusion360)
                        <img src="/images/fusion-360-logo.png" height="20" width="20">
                    @endif
                </td>
                <td>
                    @include('layouts.user', ['user' => $project->user])<br/>
                    <span class="table--text-light">{{ $project->project_country }}</span>
                </td>

                <td>
                    {!! $project->profile->text_status !!}
                    @if($project->is_deleted)
                        <br/><i class="fa fa-trash" title="deleted"></i> <span class="table--text-light">{{$project->textDeletedTime()}}</span>
                    @else
                        <br/><span class="table--text-light">{{ $project->textSubmitTime() }}</span>
                    @endif
                    @if(Auth::user()->isManagerHead() || Auth::user()->isAdmin())
                        @if(!$project->profile->isDraft())
                            <br/><a href="{{ $project->textScheduleFrontEditLink() }}" target="_blank" class="btn-mini">Schedule</a>
                            @if(!$project->hub_approve)
                            <br/><a href="{!! action('HubController@approveSchedule', $project->project_id) !!}"
                                    class="btn-mini btn-danger js-approve"><i class="fa fa-pencil fa-fw"></i>APPROVE</a>
                            @endif
                        @endif
                    @endif
                </td>

                <td>
                    @if($project->textCompanyUrl())
                        <a href="{{ $project->textCompanyUrl() }}">
                            {{ $project->textCompanyName() }}
                        </a>
                    @else
                        {{ $project->textCompanyName() }}
                    @endif
                </td>

                <td>
                    @include('project.assigned-pm')
                    @include('project.recommend-expert')
                </td>

                <td>
                    @include('project.list-propose-statistic', ['propose_solution' => $project->proposeSolutionCount($pm_ids)])
                </td>
                <td>
                    @include('project.list-recommend-statistic', ['recommend_expert' => $project->recommendExpertCount($pm_ids)])
                </td>
                <td>
                    Community:{{ $project->getPageViewCount() }}<br/>
                    Staff Referrals:{{ $project->getStaffReferredCount($pm_ids) }} <br/>
                    Collaborators:{{ $project->getCollaboratorsCount() }}
                </td>
                <td>
                    {{ implode(' / ', $project->getMappingTag($tag_tree, 2)) }} <br/>
                    @if ($project->internalProjectMemo)
                        <span class="table--text-light"> {{ str_replace(',', ' / ', $project->internalProjectMemo->tags) }} </span><br/>
                        <a href="javascript:void(0)" class="btn-mini internal-tag" rel="{!! $project->project_id !!}" tags="{{ $project->internalProjectMemo->tags }}" tech-tags="{{ implode(' / ', $project->getMappingTag($tag_tree)) }}">Add</a>
                    @else
                        <a href="javascript:void(0)" class="btn-mini internal-tag" rel="{!! $project->project_id !!}" tags="" tech-tags="{{ implode(' / ', $project->getMappingTag($tag_tree)) }}">Add</a>
                    @endif

                </td>

                <td>
                    @if ($project->internalProjectMemo)
                        @if($project->internalProjectMemo->description)
                            <a href="javascript:void(0)" class="internal-description" rel="{!! $project->project_id !!}" description="{{ $project->internalProjectMemo->description }}">
                                <i class="fa fa-pencil"></i>{{ mb_strimwidth($project->internalProjectMemo->description, 0, 130, mb_substr($project->internalProjectMemo->description, 0, 130) . '...') }}
                            </a>
                        @else
                            <a href="javascript:void(0)" class="btn-mini internal-description" rel="{!! $project->project_id !!}" description="{{ $project->internalProjectMemo->description }}">Add</a>
                        @endif
                    @else
                        <a href="javascript:void(0)" class="btn-mini internal-description" rel="{!! $project->project_id !!}" description="">Add</a>
                    @endif
                </td>

                <td>
                    {{ $project->textLastUpdateTime() }} <br/>
                    @include('layouts.user', ['user' => $project->lastEditorUser])
                </td>

                <td>
                    @if($project->isDeleted())
                        <a href="" class="btn btn-primary btn-disabled" disabled>Project deleted</a>
                    @else
                        {!!
                            link_to_action('ProjectController@showDetail', 'DETAIL',
                                $project->project_id, ['class' => 'btn-mini'])
                        !!}

                        {!!
                            link_to_action('ProjectController@showUpdate', 'EDIT',
                                $project->project_id, ['class' => 'btn-mini'])
                        !!}
                    @endif
                </td>
            </tr>
            @endforeach
        </table>

    </div>
    @include('project.dialog.schedule-grade-dialog')
    @include('project.dialog.email-recommend-expert-dialog')
    @include('project.dialog.add-tags-dialog')
    @include('project.dialog.description-dialog')
    @include('project.dialog.schedule-manager-dialog')
    @include('project.dialog.propose-solution-dialog')
    @include('project.dialog.recommend-expert-dialog')
</div>

@if($show_paginate)
    @include('layouts.paginate', [
        'collection' => $projects,
        'per_page' => isset($per_page)? $per_page : ''
    ])
@endif

@stop

