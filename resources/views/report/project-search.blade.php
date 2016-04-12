{!! Form::open(['action' => ['ReportController@showProjectReport'], 'method' => 'GET']) !!}

<div class="row">
    <div class="col-md-2 col-md-offset-1">
        <div class="input-group">
            {!! Form::select('status',[
                'all'     => 'All Status',
                'public'  => 'Expert Mode',
                'private' => 'Private Mode',
                'draft'   => 'Unfinished Draft'
            ], Input::get('status'), ['class'=>'form-control']) !!}
        </div>
    </div>

    <div class="col-md-2">
        <div class="input-group">
            {!! Form::text('project_id', Input::get('project_id'), ['placeholder'=>"Project ID", 'class'=>"form-control"]) !!}
        </div>
    </div>

    <div class="col-md-2">
        <div class="input-group">
            {!! Form::text('user_name', Input::get('user_name'), ['placeholder'=>"Owner name", 'class'=>"form-control"]) !!}
        </div>
    </div>

    <div class="col-md-2">
        <div class="input-group">
            {!! Form::text('assigned_pm', Input::get('assigned_pm'), ['placeholder'=>"Assigned PM", 'class'=>"form-control"]) !!}
        </div>
    </div>

    <div class="col-md-2">
        <div class="input-group">
            {!! Form::text('report_action', Input::get('report_action'), ['placeholder'=>"Action Keywords", 'class'=>"form-control"]) !!}
        </div>
    </div>


</div>
<div class="row">
    <div class="col-md-4 col-md-offset-1">
        <div class="input-group">
            {!! Form::select('time_type',[
                'match'   => 'Match',
                'create'  => 'Created On',
                'update'  => 'Project Last Update'
            ], $input['time_type'], ['class'=>'form-control sel-input']) !!}
            {!! Form::text('dstart', $input['dstart'],
                ['placeholder'=>"Time From", 'class'=>"form-control sel-input", 'id' => 'js-datepicker-sdate']) !!}
            {!! Form::text('dend', $input['dend'],
                ['placeholder'=>"To", 'class'=>"form-control sel-input", 'id' => 'js-datepicker-edate']) !!}

        </div>
    </div>
    <div class="col-md-2">
            <span class="input-group-btn">
                    <button class="btn btn-primary js-btn-search" type="button">Go Search!</button>
            </span>
    </div>
</div>
{!! Form::close() !!}