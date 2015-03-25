<div class="row search-bar">
    <div class="col-md-3">
        {!! Form::open(['action' => ['ProductController@showSearch', 'name'], 'method' => 'GET']) !!}
            <div class="input-group">
                {!! Form::text('name', '', ['placeholder'=>"Search by user name", 'class'=>"form-control"]) !!}
                <span class="input-group-btn">
                    <button class="btn btn-default js-btn-search" type="button">Go!</button>
                </span>
            </div>
        {!! Form::close() !!}
    </div>

    <div class="col-md-3">
        {!! Form::open(['action' => ['ProductController@showSearch', 'project_id'], 'method' => 'GET']) !!}
            <div class="input-group">
                {!! Form::text('project_id', '', ['placeholder'=>"Search by product id", 'class'=>"form-control"]) !!}
                <span class="input-group-btn">
                    <button class="btn btn-default js-btn-search" type="button">Go!</button>
                </span>
            </div>
        {!! Form::close() !!}
    </div>
</div>

<div class="row search-bar">
    <div class="col-md-3">
        {!! Form::open(['action' => ['ProductController@showSearch', 'title'], 'method' => 'GET']) !!}
            <div class="input-group">
                {!! Form::text('title', '', ['placeholder'=>"Search by project title", 'class'=>"form-control"]) !!}
                <span class="input-group-btn">
                    <button class="btn btn-default js-btn-search" type="button">Go!</button>
                </span>
            </div>
        {!! Form::close() !!}
    </div>

    <div class="col-md-3">
        {!! Form::open(['action' => ['ProductController@showSearch', 'date'], 'method' => 'GET']) !!}
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

    <div class="col-md-3">
        @if($has_wait_approve_product)
            {{
                link_to_action("ProductController@showWaitApproves",
                        'APPROVE PENDING PRODUCTS', '',["class"=>"btn btn-mini btn-warning"])
            !!}
        @else
            <a href="#" class="btn btn-mini btn-warning" disabled="disabled">APPROVE PENDING PRODUCTS</a>
        @endif
    </div>
</div>


