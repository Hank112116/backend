{!! Form::open(['action' => ['ReportController@showEventReport', $event_id], 'method' => 'GET', 'name' => 'search-form']) !!}
<div class="row text-center search-bar col-md-offset-0">
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
            <li>
                {!! link_to_action('ReportController@showQuestionnaire','Tour Form', ['event' => $event_id], null) !!}
            </li>
        </ul>
    </div>

    <div class="col-md-1">
        <div class="input-group">
            {!! Form::select('role',[
                'all'     => 'All Role',
                'creator'  => 'Creator',
                'expert' => 'Expert',
            ], Input::get('role'), ['class'=>'form-control']) !!}
        </div>
    </div>
    <div class="col-md-2">
        <div class="input-group">
            {!! Form::text('project', Input::get('project'), ['title' => 'Project Title or ID' ,'placeholder'=>"Project Title or ID", 'class'=>"form-control"]) !!}
        </div>
    </div>

    <div class="col-md-2">
        <div class="input-group">
            {!! Form::text('user', Input::get('user'), ['title' => 'User Title or ID', 'placeholder'=>"User Name or ID", 'class'=>"form-control"]) !!}
        </div>
    </div>

    <div class="col-md-1">
        <div class="input-group">
            {!! Form::text('company', Input::get('company'), ['title' => 'Company Name', 'placeholder'=>"Company", 'class'=>"form-control"]) !!}
        </div>
    </div>

    <div class="col-md-2">
        <div class="input-group">
            {!! Form::text('assigned_pm', Input::get('assigned_pm'), ['title' => 'Assigned PM or Follow PM', 'placeholder'=>"Assigned PM", 'class'=>"form-control"]) !!}
        </div>
    </div>

    <div class="col-md-1">
        <div class="input-group">
            {!! Form::text('email', Input::get('email'), ['placeholder'=>"Email", 'class'=>"form-control"]) !!}
        </div>
    </div>

</div>
<div class="row search-bar col-md-offset-0">

    <div class="col-md-2">
        <h4>Showing Scope Date</h4>
        <div class="input-group">

            {!! Form::text('dstart', $dstart,
                ['placeholder'=>"Time From", 'class'=>"form-control date-input", 'id' => 'js-datepicker-sdate']) !!}
            {!! Form::text('dend', $dend,
                ['placeholder'=>"To", 'class'=>"form-control date-input", 'id' => 'js-datepicker-edate']) !!}

        </div>
    </div>
    <div class="col-md-2">
        <span class="input-group-btn">
                <button class="btn btn-primary js-btn-search" type="button" style="margin-top: 39px;">Go Search!</button>
        </span>
    </div>
</div>
{!! Form::close() !!}
