{!! Form::open(['action' => ['ProjectController@showSearch'], 'method' => 'GET', 'name' => 'search-form']) !!}
<div class="row search-bar">
    <div class="col-md-2 col-md-offset-1">
        <div class="input-group">
            {!! Form::text('project_title', request('project_title'), ['placeholder'=>"Project title", 'class'=>"form-control"]) !!}
        </div>
    </div>

    <div class="col-md-2">
        <div class="input-group">
            {!! Form::text('project_id', request('project_id'), ['placeholder'=>"Project ID", 'class'=>"form-control"]) !!}
        </div>
    </div>

    <div class="col-md-2">
        <div class="input-group">
            {!! Form::text('user_name', request('user_name'), ['placeholder'=>"Owner name", 'class'=>"form-control"]) !!}
        </div>
    </div>

    <div class="col-md-2">
        <div class="input-group">
            {!! Form::text('assigned_pm', request('assigned_pm'), ['placeholder'=>"Assigned PM", 'class'=>"form-control"]) !!}
        </div>
    </div>

    <div class="col-md-2">
        <div class="input-group">
            {!! Form::text('description', request('description'), ['placeholder'=>"Internal description", 'class'=>"form-control"]) !!}
        </div>
    </div>


</div>

<div class="row search-bar">
    <div class="col-md-4 col-md-offset-1">
        <div class="input-group">
            {!! Form::select('time_type',[
                'update'  => 'Project Last Update',
                'create'  => 'Created On',
                'release' => 'Email Out'
            ], request('time_type'), ['class'=>'form-control sel-input']) !!}
            {!! Form::text('dstart', request('dstart'),
                ['placeholder'=>"Time From", 'class'=>"form-control sel-input", 'id' => 'js-datepicker-sdate']) !!}
            {!! Form::text('dend', request('dend'),
                ['placeholder'=>"To", 'class'=>"form-control sel-input", 'id' => 'js-datepicker-edate']) !!}

        </div>
    </div>

    <div class="col-md-2">
        <div class="input-group">
            {!! Form::text('country', request('country'), ['placeholder'=>"Country", 'class'=>"form-control"]) !!}
        </div>
    </div>

    <div class="col-md-2 ">
        <div class="input-group">
            {!! Form::text('tag', request('tag'), ['placeholder'=>"Feature tags", 'class'=>"form-control"]) !!}
        </div>
    </div>

    <div class="col-md-2">
        <div class="input-group">
            {!! Form::text('report_action', request('report_action'), ['placeholder'=>"Action Keywords", 'class'=>"form-control"]) !!}
        </div>
    </div>
</div>

<div class="row search-bar">
    <div class="col-md-2 col-md-offset-1">
        <div class="input-group">
            {!! Form::select('status',[
                'all'     => 'All Status',
                'public'  => 'Expert Mode',
                'private' => 'Private Mode',
                'draft'   => 'Unfinished Draft',
                'deleted' => 'Deleted'
            ], request('status'), ['class'=>'form-control']) !!}
        </div>
    </div>

    <div class="col-md-2">
        <span class="input-group-btn">
                <button class="btn btn-primary js-btn-search" type="button">Go Search!</button>
        </span>
    </div>

    <div class="col-md-4">
        <i class="fa fa-envelope fa-fw fa-2x" style="color: #d9534f"></i>
        {!! link_to_action('ProjectController@showSearch', $projects->not_recommend_count . ' schedule email not sent',
         ['status' => 'not-yet-email-out'], ['target' => '_blank']) !!}
    </div>
</div>

{!! Form::close() !!}