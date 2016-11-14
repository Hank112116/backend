<div class="col-md-3">
    {!! Form::open(['action' => ['TransactionController@showSearch', 'name'], 'method' => 'GET']) !!}
        <div class="input-group">
            {!! Form::text('name', request('name'), ['placeholder'=>"Search by user name", 'class'=>"form-control"]) !!}
            <span class="input-group-btn">
                <button class="btn btn-default js-btn-search" type="button">Go!</button>
            </span>
        </div>
    {!! Form::close() !!}
</div>

<div class="col-md-3">
    {!! Form::open(['action' => ['TransactionController@showSearch', 'title'], 'method' => 'GET']) !!}
        <div class="input-group">
            {!! Form::text('title', request('title'), ['placeholder'=>"Search by project title", 'class'=>"form-control"]) !!}
            <span class="input-group-btn">
                <button class="btn btn-default js-btn-search" type="button">Go!</button>
            </span>
        </div>
    {!! Form::close() !!}
</div>

<div class="col-md-3">
    {!! Form::open(['action' => ['TransactionController@showSearch', 'date'], 'method' => 'GET']) !!}
        <div class="input-group">
            {!! Form::text('dstart', '', 
                ['placeholder'=>"Search From", 'class'=>"form-control date-input", 'id' => 'js-datepicker-sdate']) !!}
            {!! Form::text('dend', '', 
                ['placeholder'=>"To", 'class'=>"form-control date-input js-datepicker", 'id' => 'js-datepicker-edate']) !!}
            <span class="input-group-btn">
                <button class="btn btn-default js-btn-search" type="button">Go!</button>
            </span>
        </div>
    {!! Form::close() !!}
</div>


