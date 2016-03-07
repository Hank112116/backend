<div class="row text-center search-bar" style="margin-left: 0px;">
    <div class="col-md-1">
        <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown">{!! $event_short_name !!}
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
            @foreach($event_list as $key => $event)
                <li>
                    {!! link_to_action('ReportController@showQuestionnaire', $event['short'], ['event_id' => $key], null) !!}
                </li>
            @endforeach
        </ul>
    </div>

    <div class="col-md-2">
        <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown">Tour Form
            <span class="caret"></span></button>
        <ul class="dropdown-menu">
            <li>
                {!! link_to_action('ReportController@showEventReport', 'Incomplete', [ 'event' => $event_id, 'complete' => 0], null) !!}
            </li>
            <li>
                {!! link_to_action('ReportController@showEventReport', 'Complete', ['event' => $event_id, 'complete' => 1], null) !!}
            </li>
            <li>
                {!! link_to_action('ReportController@showQuestionnaire', 'Tour Form', ['event' => $event_id], null) !!}
            </li>
        </ul>
    </div>

    @if($event_id == 1)
        <div class="col-md-2">
            <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown">
                @if(Input::get('participation') == 'shenzhen')
                    SZ
                @elseif(Input::get('participation') == 'beijing')
                    BJ
                @elseif(Input::get('participation') == 'taipei')
                    TW
                @else
                    All
                @endif
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
                <li>
                    {!! link_to_action('ReportController@showQuestionnaire', 'All', [ 'event' => $event_id, 'participation' => ''], null) !!}
                </li>
                <li>
                    {!! link_to_action('ReportController@showQuestionnaire', 'SZ', ['event' => $event_id, 'participation' => 'shenzhen'] , null) !!}
                </li>
                <li>
                    {!! link_to_action('ReportController@showQuestionnaire', 'BJ', ['event' => $event_id, 'participation' => 'beijing'] , null) !!}
                </li>
                <li>
                    {!! link_to_action('ReportController@showQuestionnaire', 'TW', ['event' => $event_id, 'participation' => 'taipei'] , null) !!}
                </li>
            </ul>
        </div>
    @endif

    {!! Form::open(['action' => ['ReportController@showQuestionnaire'], 'method' => 'GET']) !!}
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
    {!! Form::hidden('event_id',$event_id) !!}
    {!! Form::close() !!}
</div>
