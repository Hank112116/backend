<div class="row text-center search-bar" style="margin-left: 0px;">
    <div class="col-md-2">
        <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown">{!! $event_name !!}
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
        @foreach($event_list as $key => $event)
            <li>
                {!! link_to_action('ReportController@showEventReport', $event, $key, null) !!}
            </li>
        @endforeach
        </ul>
    </div>
    <div class="col-md-1">
        {!! Form::open(['action' => ['ReportController@showEventReport', 'complete'], 'method' => 'GET']) !!}
        <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown">{!! $complete ? 'Complete' : 'Incomplete' !!}
            <span class="caret"></span></button>
        <ul class="dropdown-menu">
            <li>
                {!! link_to_action('ReportController@showEventReport', 'Incomplete', [ 'event' => $event_id, 'complete' => 0], null) !!}
            </li>
            <li>
                {!! link_to_action('ReportController@showEventReport', 'Complete', ['event' => $event_id, 'complete' => 1], null) !!}
            </li>
        </ul>
        {!! Form::close() !!}
    </div>

    @if($complete)
        <!--<div class="col-md-1">
            {!! Form::open(['action' => ['ReportController@showEventReport', 'approve'], 'method' => 'GET']) !!}
            <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown">
                @if($approve == 'approved')
                    Selected
                @elseif($approve == 'no-approve')
                    No Select
                @elseif(is_null($approve))
                    Show All
                @endif
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
                <li>
                    {!! link_to_action('ReportController@showEventReport', 'Selected', [ 'event' => $event_id, 'complete' => $complete, 'approve' => 'approved'], null) !!}
                </li>
                <li>
                    {!! link_to_action('ReportController@showEventReport', 'No Select', ['event' => $event_id, 'complete' => $complete, 'approve' => 'no-approve'] , null) !!}
                </li>
                <li>
                    {!! link_to_action('ReportController@showEventReport', 'Show All', ['event' => $event_id, 'complete' => $complete] , null) !!}
                </li>
            </ul>
            {!! Form::close() !!}
        </div>-->
    @endif

    {!! Form::open(['action' => ['ReportController@showEventReport', $event_id], 'method' => 'GET']) !!}
    @if($complete)
    <div class="col-md-1">
        <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown">
            @if(Input::get('role') == 'expert')
                Show Expert
            @elseif(Input::get('role') == 'creator')
                Show Creator
            @else
                Show All Role
            @endif
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
            <li>
                {!! link_to_action('ReportController@showEventReport', 'Show All Role', [ 'event' => $event_id, 'complete' => $complete, 'role' => 'all'], null) !!}
            </li>
            <li>
                {!! link_to_action('ReportController@showEventReport', 'Show Expert', ['event' => $event_id, 'complete' => $complete, 'role' => 'expert'] , null) !!}
            </li>
            <li>
                {!! link_to_action('ReportController@showEventReport', 'Show Creator', ['event' => $event_id, 'complete' => $complete, 'role' => 'creator'] , null) !!}
            </li>
        </ul>
    </div>
    @endif
    <div class="col-md-2">
        <div class="input-group">
            {!! Form::text('email', '', ['placeholder'=>"Search by user email", 'class'=>"form-control"]) !!}
            <span class="input-group-btn">
                <button class="btn btn-default js-btn-search" type="button">Go!</button>
            </span>
        </div>
    </div>
    @if($complete)
    <div class="col-md-2">
        <div class="input-group">
            {!! Form::text('user_name', '', ['placeholder'=>"Search by user name", 'class'=>"form-control"]) !!}
            <span class="input-group-btn">
            <button class="btn btn-default js-btn-search" type="button">Go!</button>
        </span>
        </div>
    </div>
    <div class="col-md-2">
        <div class="input-group">
            {!! Form::text('user_id', '', ['placeholder'=>"Search by user id", 'class'=>"form-control"]) !!}
            <span class="input-group-btn">
                <button class="btn btn-default js-btn-search" type="button">Go!</button>
            </span>
        </div>
     </div>

    <div class="col-md-4">
        <div class="input-group">
            {!! Form::text('applied_at_start', '',
                ['placeholder'=>"Search From", 'class'=>"form-control date-input", 'id' => 'js-datepicker-sdate']) !!}
            {!! Form::text('applied_at_end', '',
                ['placeholder'=>"To", 'class'=>"form-control date-input js-datepicker", 'id' => 'js-datepicker-edate']) !!}
            <span class="input-group-btn">
                <button class="btn btn-default js-btn-search" type="button">Go!</button>
            </span>
        </div>
    </div>
    @else
    <div class="col-md-4">
        <div class="input-group">
            {!! Form::text('entered_at_start', '',
                ['placeholder'=>"Search Enter Time From", 'class'=>"form-control date-input", 'id' => 'js-datepicker-sdate']) !!}
            {!! Form::text('entered_at_end', '',
                ['placeholder'=>"To", 'class'=>"form-control date-input js-datepicker", 'id' => 'js-datepicker-edate']) !!}
            <span class="input-group-btn">
            <button class="btn btn-default js-btn-search" type="button">Go!</button>
        </span>
        </div>
    </div>
    @endif
    {!! Form::hidden('complete',$complete) !!}
    {!! Form::close() !!}
</div>