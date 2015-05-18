<div class="row search-bar">

    <div class="col-md-3">
        {!! Form::open(['action' => ['UserController@showSearch', 'name'], 'method' => 'GET']) !!}
            <div class="input-group">
                {!! Form::text('name', '', ['placeholder'=>"Search by user name", 'class'=>"form-control"]) !!}
                <span class="input-group-btn">
                    <button class="btn btn-default js-btn-search" type="button">Go!</button>
                </span>
            </div>
        {!! Form::close() !!}
    </div>

    <div class="col-md-2">
        {!! Form::open(['action' => ['UserController@showSearch', 'user_id'], 'method' => 'GET']) !!}
            <div class="input-group">
                {!! Form::text('user_id', '', ['placeholder'=>"Search by user id", 'class'=>"form-control"]) !!}
                <span class="input-group-btn">
                    <button class="btn btn-default js-btn-search" type="button">Go!</button>
                </span>
            </div>
        {!! Form::close() !!}
    </div>

    <div class="col-md-3">
        {!! Form::open(['action' => ['UserController@showSearch', 'email'], 'method' => 'GET']) !!}
            <div class="input-group">
                {!! Form::text('email', '', ['placeholder'=>"Search by user email", 'class'=>"form-control"]) !!}
                <span class="input-group-btn">
                    <button class="btn btn-default js-btn-search" type="button">Go!</button>
                </span>
            </div>
        {!! Form::close() !!}
    </div>

    <div class="col-md-3">
        {!! Form::open(['action' => ['UserController@showSearch', 'company'], 'method' => 'GET']) !!}
            <div class="input-group">
                {!! Form::text('company', '', ['placeholder'=>"Search by user company", 'class'=>"form-control"]) !!}
                <span class="input-group-btn">
                    <button class="btn btn-default js-btn-search" type="button">Go!</button>
                </span>
            </div>
        {!! Form::close() !!}
    </div>

    <div class="col-md-4">
        {!! Form::open(['action' => ['UserController@showSearch', 'date'], 'method' => 'GET']) !!}
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

    <div class="col-md-7">
        {!! link_to_action("UserController@showExperts", 'ALL EXPERT', '',["class"=>"btn btn-mini"]) !!}

        @if(!$is_restricted)
            {!! link_to_action("UserController@showCreators",'ALL CREATOR', '',["class"=>"btn btn-mini"]) !!}
        @endif

        @if(count($to_expert_ids) > 0)
            {!! link_to_action("UserController@showToBeExperts", 'TO BE EXPERT','',["class"=>"btn btn-mini btn-warning"]) !!}
        @else
            <a href="#" class="btn btn-mini btn-warning btn-disable" disabled="disabled">TO BE EXPERT</a>
        @endif
    </div>

</div>




