{!! Form::open(['action' => ['ReportController@showEventReport', $event_id], 'method' => 'GET', 'name' => 'search-form']) !!}
<div class="row search-bar col-md-offset-0">
    <div class="col-md-2">
        <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown">{!! $event_short_name !!} Apply
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
            @foreach($event_list as $key => $event)
                <li>
                    {!! link_to_action('ReportController@showEventReport', $event['short']. ' Apply', $key, null) !!}
                </li>
                <li>
                    {!! link_to_action('ReportController@showQuestionnaire',$event['short'] . ' Form', ['event' => $key], null) !!}
                </li>
            @endforeach
        </ul>
    </div>
</div>
<div class="row search-bar col-md-offset-0">

    <div class="col-md-2">
        <h5>Showing Date Range (PST)</h5>
        <div class="input-group">

            {!! Form::text('dstart', $dstart,
                ['placeholder'=>"Time From", 'class'=>"form-control date-input", 'id' => 'js-datepicker-sdate']) !!}
            {!! Form::text('dend', $dend,
                ['placeholder'=>"To", 'class'=>"form-control date-input", 'id' => 'js-datepicker-edate']) !!}

        </div>
    </div>
    <div class="col-md-2">
        <span class="input-group-btn">
                <button class="btn btn-primary js-btn-search" type="button" style="margin-top: 34px;">Go!</button>
        </span>
    </div>
</div>
{!! Form::close() !!}
