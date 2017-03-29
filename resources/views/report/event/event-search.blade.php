{!! Form::open(['action' => ['ReportController@showEventReport', $event_id], 'method' => 'GET', 'name' => 'search-form']) !!}
<div class="row search-bar col-md-offset-0">
    <div class="col-md-2">
        <h5>Search For:</h5>
    </div>
</div>
<div class="row search-bar col-md-offset-0">
    <div class="col-md-2">
        <div class="input-group">
            {!! Form::select('role',[
                'all'               => 'All Role',
                'creator'           => 'Creator',
                'premium-creator'   => 'Premium Creator',
                'expert'            => 'Expert',
                'premium-expert'    => 'Premium Expert',
                'pm'                => 'HWTrek PM'
            ], request('role'), ['class'=>'form-control']) !!}
        </div>
    </div>

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

    <div class="col-md-1">
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
{!! Form::close() !!}
