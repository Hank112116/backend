<div class="row search-bar">
    <div class="col-md-4">
        {!! Form::open(['action' => ['ProjectController@showSearch', 'name'], 'method' => 'GET']) !!}
            <div class="input-group">
                {!! Form::text('name', '', ['placeholder'=>"Search by user name", 'class'=>"form-control"]) !!}
                <span class="input-group-btn">
                    <button class="btn btn-default js-btn-search" type="button">Go!</button>
                </span>
            </div>
        {!! Form::close() !!}
    </div>

    <div class="col-md-4">
        {!! Form::open(['action' => ['ProjectController@showSearch', 'project_id'], 'method' => 'GET']) !!}
            <div class="input-group">
                {!! Form::text('project_id', '', ['placeholder'=>"Search by project id", 'class'=>"form-control"]) !!}
                <span class="input-group-btn">
                    <button class="btn btn-default js-btn-search" type="button">Go!</button>
                </span>
            </div>
        {!! Form::close() !!}
    </div>
</div>

<div class="row search-bar">
    <div class="col-md-4">
        {!! Form::open(['action' => ['ProjectController@showSearch', 'title'], 'method' => 'GET']) !!}
            <div class="input-group">
                {!! Form::text('title', '', ['placeholder'=>"Search by project title", 'class'=>"form-control"]) !!}
                <span class="input-group-btn">
                    <button class="btn btn-default js-btn-search" type="button">Go!</button>
                </span>
            </div>
        {!! Form::close() !!}
    </div>


    <div class="col-md-4">
        {!! Form::open(['action' => ['ProjectController@showSearch', 'date'], 'method' => 'GET']) !!}
            <div class="input-group">
                {!! Form::text('dstart', '',
                    ['placeholder'=>"Last Update", 'class'=>"form-control date-input", 'id' => 'js-datepicker-sdate']) !!}

                {!! Form::text('dend', '',
                    ['placeholder'=>"To", 'class'=>"form-control date-input", 'id' => 'js-datepicker-edate']) !!}

                <span class="input-group-btn">
                    <button class="btn btn-default js-btn-search" type="button">Go!</button>
                </span>
            </div>
        {!! Form::close() !!}
    </div>

    <div class="col-md-4">
        {!! link_to_action("ProjectController@showDeletedProjects", 'Deleted Projects', '', ["class"=>"btn btn-mini btn-primary"]) !!}
    </div>
</div>