{!! Form::open(['action' => ['ProjectController@showSearch'], 'method' => 'GET']) !!}
<div class="row search-bar">
    <div class="col-md-2 col-md-offset-1">
        <div class="input-group">
            {!! Form::text('project_title', Input::get('project_title'), ['placeholder'=>"Project title", 'class'=>"form-control"]) !!}
        </div>
    </div>

    <div class="col-md-2">
        <div class="input-group">
            {!! Form::text('project_id', Input::get('project_id'), ['placeholder'=>"Project id", 'class'=>"form-control"]) !!}
        </div>
    </div>

    <div class="col-md-2">
        <div class="input-group">
            {!! Form::text('user_name', Input::get('user_name'), ['placeholder'=>"User name", 'class'=>"form-control"]) !!}
        </div>
    </div>

    <div class="col-md-2">
        <div class="input-group">
            {!! Form::text('assigned_pm', Input::get('assigned_pm'), ['placeholder'=>"Assigned PM", 'class'=>"form-control"]) !!}
        </div>
    </div>

    <div class="col-md-2">
        <div class="input-group">
            {!! Form::text('description', Input::get('description'), ['placeholder'=>"Internal Description", 'class'=>"form-control"]) !!}
        </div>
    </div>


</div>

<div class="row search-bar">

    <div class="col-md-2 col-md-offset-1">
        <div class="input-group">
            {!! Form::text('tag', Input::get('tag'), ['placeholder'=>"Tag", 'class'=>"form-control"]) !!}
        </div>
    </div>

    <div class="col-md-2">
        <div class="input-group">
            {!! Form::text('country', Input::get('country'), ['placeholder'=>"Country", 'class'=>"form-control"]) !!}
        </div>
    </div>

    <div class="col-md-4">
        <div class="input-group">
            {!! Form::select('time_type',[
                'update'  => 'Last Update',
                'create'  => 'Create',
                'release' => 'Email Out'
            ], Input::get('time_type'), ['class'=>'form-control sel-input']) !!}
            {!! Form::text('dstart', Input::get('dstart'),
                ['placeholder'=>"Time From", 'class'=>"form-control sel-input", 'id' => 'js-datepicker-sdate']) !!}
            {!! Form::text('dend', Input::get('dend'),
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