@php
    /* @var \Backend\Model\Project\Entity\BasicProject $project */
@endphp
<td>{!! $project->getId() !!}</td>
<td class="table--name">
    @if($project->isDeleted())
        {{ $project->getTitle() }}
    @else
        <a href="{!! $project->getUrl() !!}" target="_blank">
            {{ $project->getTitle() }}
        </a>
    @endif
    <br/>
    @if($project->getCategory())
        <i class="icon icon-categ-{!! $project->getCategory() !!} icon-category"
           title="{!! $project->getTextCategory() !!}"></i>
    @else
        N/A
    @endif
    @if($project->isCreatedViaFusion360())
        <img src="/images/fusion-360-logo.png" height="20" width="20">
    @endif
</td>
<td>
    <a href="{!! $project->getOwnerUrl() !!}" target="_blank">
        {{ $project->getOwnerFullName() }}
    </a><br/>
    <span class="table--text-light">{{ $project->getCountry() }}</span>
</td>

<td>
    {{ $project->getTextStatus() }}
    @if($project->isDeleted())
        <br/><i class="fa fa-trash" title="deleted"></i>
        <span class="table--text-light">
            @if($project->getDeletedReason())
                {{ $project->getDeletedReason() }}
            @endif
            {{$project->getTextDeletedAt()}}
        </span>
    @else
        <br/>
        @if($project->getTextSubmittedAt())
            <span class="table--text-light">{{ $project->getTextSubmittedAt() }}</span>
        @else
            <span class="table--text-light">{{ $project->getTextLastUpdatedAt() }}</span>
        @endif
    @endif
    @if(auth()->user()->isManagerHead() || auth()->user()->isAdmin())
        @if(!$project->isDraft() and !$project->isDeleted())
            <br/><a href="{{ $project->getScheduleUrl() }}" target="_blank" class="btn-mini">Schedule</a>
            @if(!$project->isApprovedSchedule())
                <br/><a href="javascript:void(0)"
                        class="btn-mini btn-danger js-approve" rel="{{ $project->getId() }}"><i class="fa fa-pencil fa-fw"></i>APPROVE</a>
            @endif
        @endif
    @endif
</td>

<td>
    @if($project->getCompanyUrl())
         <a href="{{ $project->getCompanyUrl() }}">
             {{ $project->getCompanyName() }}
         </a>
     @else
         {{ $project->getCompanyName() }}
     @endif
 </td>

 <td>
     @include('project.assigned-pm')
     @if(!auth()->user()->isEditor())
         @include('project.recommend-expert')
     @endif
</td>
<td>
    Community:{{ $project->countPageView() }}<br/>
    Staff Referrals:{{ $project->countStaffReferred() }} <br/>
    Collaborators:{{ $project->countCollaborators() }}
</td>
<td>
    @if ($project->getMemoDescription())
        <a href="javascript:void(0)" class="internal-description" rel="{!! $project->getId() !!}" description="{{ $project->getMemoDescription() }}">
            <i class="fa fa-pencil"></i>{{ str_limit($project->getMemoDescription(), 130) }}
        </a>
    @else
        <a href="javascript:void(0)" class="btn-mini internal-description" rel="{!! $project->getId() !!}" description="">Add</a>
    @endif
</td>
<td>
    @if ($project->getMemoReportAction())
        <a href="javascript:void(0)" class="project-report-action" rel="{!! $project->getId() !!}" action="{{ $project->getMemoReportAction() }}">
            <i class="fa fa-pencil"></i>{{ str_limit($project->getMemoReportAction(), 130) }}
        </a>
    @else
        <a href="javascript:void(0)" class="btn-mini project-report-action" rel="{!! $project->getId() !!}" action="">Action</a>
    @endif
</td>
<td>
    {{ $project->getTextTags(' / ', 2) }} <br/>
    @if ($project->getMemoTags())
        <span class="table--text-light"> {{ $project->getTextMemoTags(' / ', 2) }} </span><br/>
        <a href="javascript:void(0)" class="btn-mini internal-tag" rel="{!! $project->getId() !!}" tags="{{ json_encode($project->getMemoTags()) }}" tech-tags="{{ $project->getTextTags(' / ') }}">Add</a>
    @else
        <a href="javascript:void(0)" class="btn-mini internal-tag" rel="{!! $project->getId() !!}" tags="" tech-tags="{{ $project->getTextTags(' / ') }}">Add</a>
    @endif

</td>
<td>
    {{ $project->getTextLastUpdatedAt() }} <br/>
    <a href="{!! $project->getLastEditorUrl() !!}" target="_blank">
        {{ $project->getLastEditorName() }}
    </a>
</td>

<td>
    @if($project->isDeleted())
        <a href="" class="btn btn-primary btn-disabled" disabled>Project deleted</a>
    @else
        {!!
            link_to_action('ProjectController@showDetail', 'DETAIL',
                $project->getId(), ['class' => 'btn-mini'])
        !!}
        @if(!auth()->user()->isEditor())
            {!!
                link_to_action('ProjectController@showUpdate', 'EDIT',
                    $project->getId(), ['class' => 'btn-mini'])
            !!}
        @endif
    @endif
</td>
