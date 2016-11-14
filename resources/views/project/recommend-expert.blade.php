@if($project->hub_approve and !$project->isDeleted() and !$project->profile->isDraft())
    @if(Carbon::parse(env('SHOW_DATE'))->lt(Carbon::parse($project->date_added))
        and !$project->isDeleted()
    )
        @if(!$project->recommendExpertTime())
            <a class="btn-mini btn-danger sendmail" href="javascript:void(0)" projectId="{{ $project->project_id }}"
               projectTitle="{{ $project->textTitle() }}" userId="{{ $project->user_id }}" PM="{!! $project->textProjectManagers() !!}">
                <i class="fa fa-envelope fa-fw"></i> SEND
            </a>
        @else
            <span class="table--text-light">
                {{ $project->recommendExpertTime() }}<br>
                @if ($project->recommendExperts[0]->adminer)
                    by {{ $project->recommendExperts[0]->adminer->name }}
                @else
                    Exception
                @endif
            </span><br/>
        @endif
    @else
        <i class="fa fa-times" aria-hidden="true"></i> <i class="fa fa-envelope fa-fw"></i>
    @endif
@endif

@if(auth()->user()->isManagerHead() || auth()->user()->isAdmin())
    @if ($project->internalProjectMemo)
        @if($project->internalProjectMemo->schedule_note_grade == 'not-graded' && is_null($project->internalProjectMemo->schedule_note))
            <div class="process-btns">
                <a href="javascript:void(0)"
                   class="btn-mini btn-danger grade" rel="{!! $project->project_id !!}" note="{{ $project->internalProjectMemo->schedule_note }}" grade="{!! $project->internalProjectMemo->schedule_note_grade !!}">
                    <i class="fa fa-pencil fa-fw"></i>Grade</a>
            </div>
        @else
            <a href="javascript:void(0)"
               class="grade" rel="{!! $project->project_id !!}" note="{{ $project->internalProjectMemo->schedule_note }}" grade="{!! $project->internalProjectMemo->schedule_note_grade !!}">
                {{ $project->textNoteGrade() }}@if($project->internalProjectMemo->schedule_note != null ):@endif {{ $project->internalProjectMemo->schedule_note }}
            </a>
        @endif
    @else
        <div class="process-btns">
            <a href="javascript:void(0)"
               class="btn-mini btn-danger grade" rel="{!! $project->project_id !!}" note="" grade="not-graded">
                <i class="fa fa-pencil fa-fw"></i>Grade</a>
        </div>
    @endif
@else
    @if($project->internalProjectMemo)
        @if(!is_null($project->internalProjectMemo->schedule_note))
            {{ $project->textNoteGrade() }} @if($project->internalProjectMemo->schedule_note != null ):@endif {{ $project->internalProjectMemo->schedule_note }}
        @endif
    @endif
@endif
