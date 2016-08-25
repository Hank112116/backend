{!! Form::open(['action' => ['ProjectController@showSearch'], 'method' => 'GET', 'name' => 'search-form']) !!}
<div class="row search-bar">
    <div class="col-md-2 col-md-offset-1">
        <div class="input-group">
            {!! Form::text('project_title', Input::get('project_title'), ['placeholder'=>"Project title", 'class'=>"form-control"]) !!}
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
            {!! Form::text('description', Input::get('description'), ['placeholder'=>"Internal description", 'class'=>"form-control"]) !!}
        </div>
    </div>


</div>

<div class="row search-bar">

    <div class="col-md-2 col-md-offset-1">
        <div class="input-group">
            {!! Form::text('tag', Input::get('tag'), ['placeholder'=>"Feature tags", 'class'=>"form-control"]) !!}
        </div>
    </div>

    <div class="col-md-2">
        <div class="input-group">
            {!! Form::text('country', Input::get('country'), ['placeholder'=>"Country", 'class'=>"form-control"]) !!}
        </div>
    </div>

    <div class="col-md-2">
        <div class="input-group">
            {!! Form::select('status',[
                'all'     => 'All Status',
                'public'  => 'Expert Mode',
                'private' => 'Private Mode',
                'draft'   => 'Unfinished Draft',
                'deleted' => 'Deleted'
            ], Input::get('status'), ['class'=>'form-control']) !!}
        </div>
    </div>

    <div class="col-md-4">
        <div class="input-group">
            {!! Form::select('time_type',[
                'update'  => 'Project Last Update',
                'create'  => 'Created On',
                'release' => 'Email Out'
            ], Input::get('time_type'), ['class'=>'form-control sel-input']) !!}
            {!! Form::text('dstart', Input::get('dstart'),
                ['placeholder'=>"Time From", 'class'=>"form-control sel-input", 'id' => 'js-datepicker-sdate']) !!}
            {!! Form::text('dend', Input::get('dend'),
                ['placeholder'=>"To", 'class'=>"form-control sel-input", 'id' => 'js-datepicker-edate']) !!}

        </div>
    </div>
</div>

<div class="row search-bar">
    <div class="col-md-2 col-md-offset-1">
        <span class="input-group-btn">
                <button class="btn btn-primary js-btn-search" type="button">Go Search!</button>
        </span>
    </div>
    <div class="col-md-4">
        <i class="fa fa-envelope fa-fw fa-2x" style="color: #d9534f"></i>
        {!! link_to_action('ProjectController@showSearch', 'Not yet email out: ' . $not_recommend_count,
         ['status' => 'not-yet-email-out'], ['target' => '_blank']) !!}
    </div>
</div>

{!! Form::close() !!}