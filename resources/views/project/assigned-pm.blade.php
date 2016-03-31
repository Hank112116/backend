@if (Auth::user()->role->hasCert('schedule_manager'))
    @if(!$project->hasProjectManager())
        <a href="javascript:void(0)" class="schedule-manager" rel="{{ $project->project_id }}" pm="">
            <p class="hub-manages">
                <i class="fa fa-fw fa-exclamation-triangle"></i> No PM
            </p>
        </a>
    @else
        <a href="javascript:void(0)" class="schedule-manager" rel="{{ $project->project_id }}" pm="{{ $project->getProjectManagers() }}"/>
        @foreach($project->getHubManagerNames() as $manager)
            <p class="hub-manages">
                <i class="fa fa-user fa-fw"></i> {!! $manager !!}
            </p>
        @endforeach

        @foreach($project->getDeletedHubManagerNames() as $manager)
            <p class="hub-manages">
                <i class="fa fa-flash fa-fw"></i> {!! $manager !!}
            </p>
        @endforeach
        </a>
    @endif

@else
    @if(!$project->hasProjectManager())
            <p class="hub-manages">
                <i class="fa fa-fw fa-exclamation-triangle"></i> No PM
            </p>
    @else
        @foreach($project->getHubManagerNames() as $manager)
            <p class="hub-manages">
                <i class="fa fa-user fa-fw"></i> {!! $manager !!}
            </p>
        @endforeach

        @foreach($project->getDeletedHubManagerNames() as $manager)
            <p class="hub-manages">
                <i class="fa fa-flash fa-fw"></i> {!! $manager !!}
            </p>
        @endforeach
    @endif
@endif