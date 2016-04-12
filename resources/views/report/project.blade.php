@extends('layouts.master')
@include('layouts.macro')

@section('css')
    <link rel="stylesheet" href="/css/product-list.css">
@stop

@section('jqui')
    @include('layouts.jqui')
@stop

@section('js')
    <script src='/js/list.js'></script>
    <script src='/js/project-list.js'></script>
@stop

@section('content')

    <div class="page-header">
        <h1>{!! $title !!}</h1>
    </div>

    <div class="search-bar">
        @include('report.project-search')
    </div>
    <div class="row text-center">
        <h4>
            {{ $projects->total() }} Results: {{ $projects->public_count }} Expert mode | {{ $projects->private_count }} Private mode | {{ $projects->draft_count }} Draft | {{ $projects->delete_count }} Deleted <br/><br/>
            @if ($match_statistics)
                <?php $i = 1; ?>
                [Referrals]
                @foreach($match_statistics as $name => $statistic)
                    {{ $name }}: {{ $statistic['recommend_count'] }} |
                    @if ($i % 7 == 0)
                        <br/>
                    @endif
                    <?php $i++; ?>
                @endforeach
            @endif
        </h4>
        <button class="btn btn-primary match-statistics-btn" type="button">Match Statistics</button>
        <input type="hidden" name="match-statistics" id="match-statistics" value="{{ json_encode($match_statistics) }}">
    </div><br/>
    <div class="row">
        <div class="col-md-12">
            <table class="table table-striped">
                <tr>
                    <th>#</th>
                    <th class="table--name">Project Title<br/><span class="table--text-light">Category</span></th>
                    <th>Owner<br/><span class="table--text-light">Country</span></th>
                    <th>Status<br/><span class="table--text-light">Status Change</span></th>
                    <th>Company</th>
                    <th>Assigned PM</th>
                    <th>Proposed</th>
                    <th>Referrals</th>
                    <th>Created On<br/><span class="table--text-light">Last Update</span></th>
                    <th>Action</th>
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
                        </td>

                        <td>
                            @if ($input['time_type'] == 'match')
                                @include('project.list-propose-statistic', ['propose_solution' => $project->proposeSolutionCount($pm_ids, $input['dstart'], $input['dend'])])
                            @else
                                @include('project.list-propose-statistic', ['propose_solution' => $project->proposeSolutionCount($pm_ids)])
                            @endif
                        </td>
                        <td>
                            @if ($input['time_type'] == 'match')
                                @include('project.list-recommend-statistic', ['recommend_expert' => $project->recommendExpertStatistics($input['dstart'], $input['dend'])])
                            @else
                                @include('project.list-recommend-statistic', ['recommend_expert' => $project->recommendExpertStatistics()])
                            @endif
                        </td>

                        <td>
                            {{ Carbon::parse($project->date_added)->toFormattedDateString() }} <br/>
                            <span class="table--text-light">
                            {{ Carbon::parse($project->update_time)->toFormattedDateString() }}
                            </span>
                        </td>
                        <td>
                            @if ($project->internalProjectMemo)
                                @if($project->internalProjectMemo->report_action)
                                    <a href="javascript:void(0)" class="project-report-action" rel="{!! $project->project_id !!}" action="{{ $project->internalProjectMemo->report_action }}">
                                        <i class="fa fa-pencil"></i>{{ mb_strimwidth($project->internalProjectMemo->report_action, 0, 130, mb_substr($project->internalProjectMemo->report_action, 0, 130) . '...') }}
                                    </a>
                                @else
                                    <a href="javascript:void(0)" class="btn-mini project-report-action" rel="{!! $project->project_id !!}" action="{{ $project->internalProjectMemo->report_action }}">Add</a>
                                @endif
                            @else
                                <a href="javascript:void(0)" class="btn-mini project-report-action" rel="{!! $project->project_id !!}" action="">Add</a>
                            @endif
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
            @include('project.dialog.propose-solution-dialog')
            @include('project.dialog.recommend-expert-dialog')
            @include('report.dialog.project-report-action')
            @include('report.dialog.project-match-statistics')
        </div>
    </div>
    <div class="text-center">
    {!! $projects->appends(Input::all())->render() !!}
    </div>
    @if ($input['time_type'] == 'match')
        <input type="hidden" id="statistic-start-date" value="{{ $input['dstart'] }}">
        <input type="hidden" id="statistic-end-date" value="{{ $input['dend'] }}">
    @else
        <input type="hidden" id="statistic-start-date" value="">
        <input type="hidden" id="statistic-end-date" value="">
    @endif
@stop

