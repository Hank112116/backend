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
        @if(!$project->profile->isDraft() and !$project->isDeleted())
            <br/><a href="{{ $project->textScheduleFrontEditLink() }}" target="_blank" class="btn-mini">Schedule</a>
            @if(!$project->hub_approve)
                <br/><a href="javascript:void(0)"
                        class="btn-mini btn-danger js-approve" rel="{{ $project->project_id }}"><i class="fa fa-pencil fa-fw"></i>APPROVE</a>
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
    @if(!Auth::user()->isEditor())
        @include('project.recommend-expert')
    @endif
</td>

<td>
    @include('project.list-propose-statistic', ['propose_solution' => $project->getStatistic()])
</td>
<td>
    @include('project.list-recommend-statistic', ['recommend_expert' => $project->getStatistic(), 'email_out_count' => $project->getEmailOutCount()])
</td>
<td>
    Community:{{ $project->getPageViewCount() }}<br/>
    Staff Referrals:{{ $project->getStaffReferredCount() }} <br/>
    Collaborators:{{ $project->getCollaboratorsCount() }}
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
    {{ implode(' / ', $project->getMappingTag($tag_tree, 2)) }} <br/>
    @if ($project->internalProjectMemo)
        <span class="table--text-light"> {{ str_replace(',', ' / ', $project->internalProjectMemo->tags) }} </span><br/>
        <a href="javascript:void(0)" class="btn-mini internal-tag" rel="{!! $project->project_id !!}" tags="{{ $project->internalProjectMemo->tags }}" tech-tags="{{ implode(' / ', $project->getMappingTag($tag_tree)) }}">Add</a>
    @else
        <a href="javascript:void(0)" class="btn-mini internal-tag" rel="{!! $project->project_id !!}" tags="" tech-tags="{{ implode(' / ', $project->getMappingTag($tag_tree)) }}">Add</a>
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
        @if(!Auth::user()->isEditor())
            {!!
                link_to_action('ProjectController@showUpdate', 'EDIT',
                    $project->project_id, ['class' => 'btn-mini'])
            !!}
        @endif
    @endif
</td>
