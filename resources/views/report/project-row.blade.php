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
        @include('project.list-propose-statistic', ['propose_solution' => $project->proposeSolutionCount($input['dstart'], $input['dend'])])
    @else
        @include('project.list-propose-statistic', ['propose_solution' => $project->getStatistic()])
    @endif
</td>
<td>
    @if ($input['time_type'] == 'match')
        <?php
        $recommend_expert    = $project->recommendExpertStatistics($input['dstart'], $input['dend']);
        $user_referral_total = $user_referral_total + $recommend_expert->user_referral;
        ?>
        @include('project.list-recommend-statistic', ['recommend_expert' => $recommend_expert, 'email_out_count' => 0])
    @else
        <?php
        $recommend_expert    = $project->getStatistic();
        $user_referral_total = $user_referral_total + $recommend_expert->user_referral;
        ?>
        @include('project.list-recommend-statistic', ['recommend_expert' => $recommend_expert, 'email_out_count' => $project->getEmailOutCount()])
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
            <a href="javascript:void(0)" class="btn-mini project-report-action" rel="{!! $project->project_id !!}" action="{{ $project->internalProjectMemo->report_action }}">Action</a>
        @endif
    @else
        <a href="javascript:void(0)" class="btn-mini project-report-action" rel="{!! $project->project_id !!}" action="">Action</a>
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