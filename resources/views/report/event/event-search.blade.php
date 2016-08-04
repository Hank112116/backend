{!! Form::open(['action' => ['ReportController@showEventReport', $event_id], 'method' => 'GET', 'name' => 'search-form']) !!}
<div class="row search-bar col-md-offset-0">
    <div class="col-md-1">
        <h5>Search Result:</h5>
    </div>
</div>
<div class="row search-bar col-md-offset-0">
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
            {!! Form::text('user', Input::get('user'), ['title' => 'User Title or ID', 'placeholder'=>"User Name or ID", 'class'=>"form-control"]) !!}
        </div>
    </div>

    <div class="col-md-2">
        <div class="input-group">
            {!! Form::text('project', Input::get('project'), ['title' => 'Project Title or ID' ,'placeholder'=>"Project Title or ID", 'class'=>"form-control"]) !!}
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

    <div class="col-md-1">
        <span class="input-group-btn">
                <button class="btn btn-primary js-btn-search" type="button">Go Search!</button>
        </span>
    </div>
</div>
{!! Form::close() !!}
