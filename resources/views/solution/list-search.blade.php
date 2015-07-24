<div class="row">
    <div class="col-md-4 col-md-offset-1">
        {!! Form::open(['action' => ['SolutionController@showSearch', 'name'], 'method' => 'GET']) !!}
            <div class="input-group">
                {!! Form::text('name', '', ['placeholder'=>"Search by user name", 'class'=>"form-control"]) !!}
                <span class="input-group-btn">
                    <button class="btn btn-default js-btn-search" type="button">Go!</button>
                </span>
            </div>
        {!! Form::close() !!}
    </div>

    <div class="col-md-4">
        {!! Form::open(['action' => ['SolutionController@showSearch', 'title'], 'method' => 'GET']) !!}
            <div class="input-group">
                {!! Form::text('title', '', ['placeholder'=>"Search by solution title", 'class'=>"form-control"]) !!}
                <span class="input-group-btn">
                    <button class="btn btn-default js-btn-search" type="button">Go!</button>
                </span>
            </div>
        {!! Form::close() !!}
    </div>
</div>

<div class="row">
    <div class="col-md-11 col-md-offset-1">
    @if(Auth::user()->isAdmin())
        {!! link_to_action("SolutionController@showDraftSolutions", 'Drafts', '', ["class"=>"btn btn-mini btn-primary"]) !!}
        {!! link_to_action("SolutionController@showDeletedSolutions", 'Deleted Solutions', '', ["class"=>"btn btn-mini btn-primary"]) !!}
    @endif

    @if($has_program)
        {!! link_to_action("SolutionController@showProgram", 'Program', '',
            ["class"=>"btn btn-mini btn-primary"] ) !!}
    @else   
        <a href="#" class="btn btn-mini btn-primary" disabled="disabled">Program</a>
    @endif

    @if($has_wait_approve_solutions)
        {!! link_to_action("SolutionController@showWaitApproveSolutions", 'Approve Pending Solutions', '',
            ["class"=>"btn btn-mini btn-warning"] ) !!}
    @else   
        <a href="#" class="btn btn-mini btn-warning" disabled="disabled">Approve Pending Solutions</a>
    @endif
    @if($has_pending_up_program)
        {!! link_to_action("SolutionController@showPendingProgram", 'Pending Programs', '',
            ["class"=>"btn btn-mini btn-warning"] ) !!}
    @else   
        <a href="#" class="btn btn-mini btn-warning" disabled="disabled">Pending Programs</a>
    @endif
    @if($has_pending_change_solution)
        {!! link_to_action("SolutionController@showPendingSolution", 'Pending Solutions', '',
            ["class"=>"btn btn-mini btn-warning"] ) !!}
    @else   
        <a href="#" class="btn btn-mini btn-warning" disabled="disabled">Pending Solutions</a>
    @endif


    </div>
</div>




