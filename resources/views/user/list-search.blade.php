{!! Form::open(['action' => ['UserController@showSearch'], 'method' => 'GET', 'name' => 'search-form']) !!}
<div class="row search-bar">
    <div class="col-md-2 col-md-offset-1">
        <div class="input-group">
            {!! Form::text('user_name', request('user_name'), ['placeholder'=>"User Name", 'class'=>"form-control"]) !!}
        </div>
    </div>

    <div class="col-md-2">
        <div class="input-group">
            {!! Form::text('user_id', request('user_id'), ['placeholder'=>"User ID", 'class'=>"form-control"]) !!}
        </div>
    </div>

    <div class="col-md-2">
        <div class="input-group">
            {!! Form::text('email', request('email'), ['placeholder'=>"User Email", 'class'=>"form-control"]) !!}
        </div>
    </div>

    <div class="col-md-2">
        <div class="input-group">
            {!! Form::text('company', request('company'), ['placeholder'=>"Company", 'class'=>"form-control"]) !!}
        </div>
    </div>
    <div class="col-md-2">
        <div class="input-group">
            {!! Form::text('tag', request('tag'), ['placeholder'=>"Tags", 'class'=>"form-control"]) !!}
        </div>
    </div>

</div>

<div class="row search-bar">
    <div class="col-md-4 col-md-offset-1">
        <div class="input-group">
            {!! Form::text('dstart', request('dstart'),
                ['placeholder'=>"Time From", 'class'=>"form-control date-input", 'id' => 'js-datepicker-sdate']) !!}
            {!! Form::text('dend', request('dend'),
                ['placeholder'=>"To", 'class'=>"form-control date-input", 'id' => 'js-datepicker-edate']) !!}

        </div>
    </div>

    <div class="col-md-2">
        <div class="input-group">
            @if((!isset($is_restricted) or !$is_restricted))
            {!! Form::select('status',[
                'all'            => 'All Users',
                'expert'         => 'Expert',
                'creator'        => 'Creator',
                'premium-expert' => 'Premium Expert',
                'to-be-expert'   => 'To Be Expert',
                'pm'             => 'HWTrek PM'
            ], request('status'), ['class'=>'form-control']) !!}
            @else
            {!! Form::select('status',[
                'expert'         => 'Expert',
                'premium-expert' => 'Premium Expert',
                'to-be-expert'   => 'To Be Expert'
            ], request('status'), ['class'=>'form-control']) !!}
            @endif
        </div>
    </div>

    <div class="col-md-2">
        <div class="input-group">
            {!! Form::text('description', request('description'), ['placeholder'=>"Internal description", 'class'=>"form-control"]) !!}
        </div>
    </div>

    <div class="col-md-2">
        <div class="input-group">
            {!! Form::text('action', request('action'), ['placeholder'=>"Action Keywords", 'class'=>"form-control"]) !!}
        </div>
    </div>
</div>
<div class="row search-bar">
    <div class="col-md-2 col-md-offset-1">
        <span class="input-group-btn">
                <button class="btn btn-primary js-btn-search" type="button">Go Search!</button>
        </span>
    </div>
</div>

{!! Form::close() !!}