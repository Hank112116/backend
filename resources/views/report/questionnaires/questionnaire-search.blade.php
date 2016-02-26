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

    @if($event_id == 1)
        <div class="col-md-1">
            <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown">
                @if(Input::get('participation') == 'shenzhen')
                    Shenzhen
                @elseif(Input::get('participation') == 'beijing')
                    Beijing
                @elseif(Input::get('participation') == 'taipei')
                    Taipei
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
                    {!! link_to_action('ReportController@showQuestionnaire', 'Shenzhen', ['event' => $event_id, 'participation' => 'shenzhen'] , null) !!}
                </li>
                <li>
                    {!! link_to_action('ReportController@showQuestionnaire', 'Beijing', ['event' => $event_id, 'participation' => 'beijing'] , null) !!}
                </li>
                <li>
                    {!! link_to_action('ReportController@showQuestionnaire', 'Taipei', ['event' => $event_id, 'participation' => 'taipei'] , null) !!}
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
