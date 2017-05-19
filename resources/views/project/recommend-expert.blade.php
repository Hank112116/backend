@php
    /* @var \Backend\Model\Project\Entity\BasicProject $project */
@endphp
@if($project->canRecommendExperts())
    <a class="btn-mini btn-danger sendmail" href="javascript:void(0)" projectId="{{ $project->getId() }}"
       projectTitle="{{ $project->getTitle() }}" userId="{{ $project->getOwnerId() }}" PM="{{ json_encode($project->getPMIds()) }}">
        <i class="fa fa-envelope fa-fw"></i> SEND
    </a><br/>
@else
    @if ($project->getRecommender())
    <span class="table--text-light">
            {{ $project->getTextRecommendedAt() }}<br>
            by {{ $project->getRecommender() }}
    </span><br/>
    @endif
@endif

@if(auth()->user()->isManagerHead() || auth()->user()->isAdmin())
    @if ($project->getMemoScheduleNoteGrade())
        @if($project->getMemoScheduleNoteGrade() == 'not-graded' && is_null($project->getMemoScheduleNote()))
            <div class="process-btns">
                <a href="javascript:void(0)"
                   class="btn-mini btn-danger grade" rel="{!! $project->getId() !!}" note="{{ $project->getMemoScheduleNote() }}" grade="{!! $project->getMemoScheduleNoteGrade() !!}">
                    <i class="fa fa-pencil fa-fw"></i>Grade</a>
            </div>
        @else
            <a href="javascript:void(0)"
               class="grade" rel="{!! $project->getId() !!}" note="{{ $project->getMemoScheduleNote() }}" grade="{!! $project->getMemoScheduleNoteGrade() !!}">
                {{ $project->getTextMemoScheduleNoteGrade() }}@if($project->getMemoScheduleNote() != null ):@endif {{ $project->getMemoScheduleNote() }}
            </a>
        @endif
    @else
        <div class="process-btns">
            <a href="javascript:void(0)"
               class="btn-mini btn-danger grade" rel="{!! $project->getId()!!}" note="" grade="not-graded">
                <i class="fa fa-pencil fa-fw"></i>Grade</a>
        </div>
    @endif
@else
    @if(!is_null($project->getMemoScheduleNote()))
        {{ $project->getMemoScheduleNoteGrade() }} @if($project->getMemoScheduleNote() != null ):@endif {{ $project->getMemoScheduleNote() }}
    @endif
@endif
