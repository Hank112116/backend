{!! Form::open(['action' => ['SolutionController@showSearch'], 'method' => 'GET', 'name' => 'search-form']) !!}
<div class="row search-bar">
    <div class="col-md-2 col-md-offset-1">
        <div class="input-group">
            {!! Form::text('user_name', Input::get('user_name'), ['placeholder'=>"Owner", 'class'=>"form-control"]) !!}
        </div>
    </div>

    <div class="col-md-2">
        <div class="input-group">
            {!! Form::text('solution_title', Input::get('solution_title'), ['placeholder'=>"Solution Title", 'class'=>"form-control"]) !!}
        </div>
    </div>

    <div class="col-md-2">
        <div class="input-group">
            {!! Form::text('solution_id', Input::get('solution_id'), ['placeholder'=>"Solution ID", 'class'=>"form-control"]) !!}
        </div>
    </div>
</div>

<div class="row search-bar">

    <div class="col-md-4 col-md-offset-1">
        <div class="input-group">
            {!! Form::text('dstart', Input::get('dstart'),
                ['placeholder'=>"Approve Time From", 'class'=>"form-control date-input", 'id' => 'js-datepicker-sdate']) !!}
            {!! Form::text('dend', Input::get('dend'),
                ['placeholder'=>"To", 'class'=>"form-control date-input", 'id' => 'js-datepicker-edate']) !!}

        </div>
    </div>

    <div class="col-md-2">
        <div class="input-group">
                {!! Form::select('status',[
                    'all'              => 'All Type',
                    'solution'         => 'Solution',
                    'program'          => 'Program',
                    'on-shelf'         => 'On Shelf',
                    'off-shelf'        => 'Off Shelf',
                    'pending-approve'  => 'Pending for Approve',
                    'pending-program'  => 'Pending for Program',
                    'pending-solution' => 'Pending for Solution'
                ], Input::get('status'), ['class'=>'form-control']) !!}
        </div>
    </div>

    <div class="col-md-2">
        <span class="input-group-btn">
                <button class="btn btn-primary js-btn-search" type="button">Go Search!</button>
        </span>
    </div>
</div>

{!! Form::close() !!}
