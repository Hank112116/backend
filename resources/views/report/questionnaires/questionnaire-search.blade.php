{!! Form::open(['action' => ['ReportController@showQuestionnaire'], 'method' => 'GET', 'name' => 'search-form']) !!}
<div class="row search-bar col-md-offset-0">
    <div class="col-md-2">
        <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown">{!! $event_short_name !!} Form
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

@if ($event_id == 2)
    @include('report.questionnaires.questionnaire-summary')
@elseif ($event_id == 3)
    @include('report.questionnaires.questionnaire-2017-q2-summary')
@endif

<div class="row search-bar col-md-offset-0">
    <div class="col-md-2">
        <h5>Search For:</h5>
    </div>
</div>
<div class="row search-bar col-md-offset-0">
    <div class="col-md-2">
        <div class="input-group">
            {!! Form::text('user', request('user'), ['title' => 'User Title or ID', 'placeholder'=>"User Name or ID", 'class'=>"form-control"]) !!}
        </div>
    </div>

    <div class="col-md-2">
        <div class="input-group">
            {!! Form::text('project', request('project'), ['title' => 'Project Title or ID' ,'placeholder'=>"Project Title or ID", 'class'=>"form-control"]) !!}
        </div>
    </div>

    <div class="col-md-1">
        <div class="input-group">
            {!! Form::text('company', request('company'), ['title' => 'Company Name', 'placeholder'=>"Company", 'class'=>"form-control"]) !!}
        </div>
    </div>

    <div class="col-md-2">
        <div class="input-group">
            {!! Form::text('assigned_pm', request('assigned_pm'), ['title' => 'Assigned PM or Follow PM', 'placeholder'=>"Assigned PM", 'class'=>"form-control"]) !!}
        </div>
    </div>

    <div class="col-md-2">
        <div class="input-group">
            {!! Form::text('email', request('email'), ['placeholder'=>"Email", 'class'=>"form-control"]) !!}
        </div>
    </div>

    <div class="col-md-1">
        <span class="input-group-btn">
                <button class="btn btn-primary js-btn-search" type="button">Go Search!</button>
        </span>
    </div>
</div>
{!! Form::hidden('event_id',$event_id) !!}
{!! Form::close() !!}