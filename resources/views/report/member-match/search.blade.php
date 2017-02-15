{!! Form::open(['action' => ['ReportController@showMemberMatchingReport'], 'method' => 'GET', 'name' => 'search-form']) !!}

<div class="row">

    <div class="col-md-2 col-md-offset-1">
        <div class="input-group">
            {!! Form::text('user_id', request('user_id'), ['placeholder'=>"User ID", 'class'=>"form-control"]) !!}
        </div>
    </div>

    <div class="col-md-2">
        <div class="input-group">
            {!! Form::text('user_name', request('user_name'), ['placeholder'=>"User name", 'class'=>"form-control"]) !!}
        </div>
    </div>

    <div class="col-md-2">
        <div class="input-group">
            {!! Form::text('company', request('company'), ['placeholder'=>"Company", 'class'=>"form-control"]) !!}
        </div>
    </div>

    <div class="col-md-2">
        <div class="input-group">
            {!! Form::text('action', request('action'), ['placeholder'=>"Action Keywords", 'class'=>"form-control"]) !!}
        </div>
    </div>
    <div class="col-md-2 ">
        <div class="input-group">
            {!! Form::select('status',[
                'all'            => 'All Users',
                'expert'         => 'Expert',
                'creator'        => 'Creator',
                'premium-expert' => 'Premium Expert',
                'to-be-expert'   => 'To Be Expert',
                'pm'             => 'HWTrek PM'
            ], request('status'), ['class'=>'form-control']) !!}
        </div>
    </div>

</div>
<div class="row">
    <div class="col-md-4 col-md-offset-1">
        <div class="input-group">
            {!! Form::select('time_type',[
                'match'   => 'Match',
            ], '', ['class'=>'form-control sel-input']) !!}
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
