@php
/**
 * @var \Backend\Model\Project\Entity\BasicProject $project
 * @var \Backend\Model\Project\Entity\PM           $pm
 */
@endphp
@if (auth()->user()->role->hasCert('schedule_manager')
    and !$project->isDeleted()
)
    @if(empty($project->getPMIds()))
        <a href="javascript:void(0)" class="schedule-manager" rel="{{ $project->getId() }}" pm="">
            <p class="hub-manages">
                <i class="fa fa-fw fa-exclamation-triangle"></i> No PM
            </p>
        </a>
    @else
        <a href="javascript:void(0)" class="schedule-manager" rel="{{ $project->getId() }}" pm="{{ json_encode($project->getPMIds()) }}">
        @foreach($project->getPMs() as $pm)
            <p class="hub-manages">
                <i class="fa fa-user fa-fw"></i> {{ $pm->getFirstName() }}
            </p>
        @endforeach
        </a>
    @endif

@else
    @if(empty($project->getPMIds()))
            <p class="hub-manages">
                <i class="fa fa-fw fa-exclamation-triangle"></i> No PM
            </p>
    @else
        @foreach($project->getPMs() as $pm)
            <p class="hub-manages">
                <i class="fa fa-user fa-fw"></i> {{ $pm->getFirstName() }}
            </p>
        @endforeach
    @endif
@endif