@include('layouts.macro')

<div id="{!! $feature->getEntityId() !!}" class="row js-block">
    <div class="col-sm-10 col-sm-offset-1 block">
        
        <div class="move-up-down">
            <div class="icon icon-sort-up arrow js-move-up"></div>
            <div class="icon icon-sort-down arrow js-move-down"></div>
        </div>

        @if($feature->isExpert())
            @include('landing.feature-expert',   ['user' => $feature->entity])

        @elseif($feature->isProject())
            @include('landing.feature-project',  ['project' => $feature->entity])

        @elseif ($feature->isSolution())
            @include('landing.feature-solution', ['solution' => $feature->entity])

        @elseif ($feature->isProgram())
            @include('landing.feature-program', ['program' => $feature->entity])

        @endif    
    </div>
</div>