<div class="row text-center search-bar" style="margin-left: 0px;">
    <div class="col-md-1">
        <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown">{!! $event_short_name !!}
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
        @foreach($event_list as $key => $event)
            <li>
                {!! link_to_action('ReportController@showEventReport', $event['short'], $key, null) !!}
            </li>
        @endforeach
        </ul>
    </div>

    {!! Form::open(['action' => ['ReportController@showEventReport', $event_id], 'method' => 'GET', 'name' => 'search-form']) !!}

    <div class="col-md-1">
        <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown">
            @if(Input::get('role') == 'expert')
                Expert
            @elseif(Input::get('role') == 'creator')
                Creator
            @else
                All
            @endif
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
            <li>
                {!! link_to_action('ReportController@showEventReport', 'All', [ 'event' => $event_id,  'role' => 'all'], null) !!}
            </li>
            <li>
                {!! link_to_action('ReportController@showEventReport', 'Expert', ['event' => $event_id,  'role' => 'expert'] , null) !!}
            </li>
            <li>
                {!! link_to_action('ReportController@showEventReport', 'Creator', ['event' => $event_id, 'role' => 'creator'] , null) !!}
            </li>
        </ul>
    </div>

    <div class="col-md-2">
        <div class="input-group">
            {!! Form::text('email', '', ['placeholder'=>"Email", 'class'=>"form-control"]) !!}
            <span class="input-group-btn">
                <button class="btn btn-default js-btn-search" type="button">Go!</button>
            </span>
        </div>
    </div>

    <div class="col-md-2">
        <div class="input-group">
            {!! Form::text('user_name', '', ['placeholder'=>"Name", 'class'=>"form-control"]) !!}
            <span class="input-group-btn">
            <button class="btn btn-default js-btn-search" type="button">Go!</button>
        </span>
        </div>
    </div>
    <div class="col-md-2">
        <div class="input-group">
            {!! Form::text('user_id', '', ['placeholder'=>"ID", 'class'=>"form-control"]) !!}
            <span class="input-group-btn">
                <button class="btn btn-default js-btn-search" type="button">Go!</button>
            </span>
        </div>
     </div>

    <div class="col-md-4">
        <div class="input-group">
            {!! Form::text('applied_at_start', '',
                ['placeholder'=>"Apply Time From", 'class'=>"form-control date-input", 'id' => 'js-datepicker-sdate']) !!}
            {!! Form::text('applied_at_end', '',
                ['placeholder'=>"To", 'class'=>"form-control date-input js-datepicker", 'id' => 'js-datepicker-edate']) !!}
            <span class="input-group-btn">
                <button class="btn btn-default js-btn-search" type="button">Go!</button>
            </span>
        </div>
    </div>

    {!! Form::close() !!}
</div>