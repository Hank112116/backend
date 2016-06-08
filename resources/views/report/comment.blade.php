@extends('layouts.master')
@include('layouts.macro')

@section('css')
    <link rel="stylesheet" href="{{ LinkGen::assets('css/user-list.css') }}">
@stop
@section('js')
    <script src="{{ LinkGen::assets('js/list.js') }}"></script>
@stop


@section('content')
    <div class="page-header">
        <h1>{{ $title or 'Comment Summary' }}</h1>
    </div>

    <div class="row text-center search-bar">
        {{--<div class="col-md-12">--}}
        {!! Form::open(['action' => ['ReportController@showCommentReport', 'date'], 'method' => 'GET', 'class' => 'form-inline']) !!}
        <div class="form-group has-feedback">
            <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown">{!! isset($range)?"Comment in last $range days":'Custom' !!}
                <span class="caret"></span></button>
            <ul class="dropdown-menu">
                <li>
                    {!! link_to_action('ReportController@showCommentReport', 'Comment Report in last 7 days', ['range' => 7,'filter' => Input::get('filter'), 'name' => Input::get('name'), 'id' => Input::get('id')  ], null) !!}
                </li>
                <li>
                    {!! link_to_action('ReportController@showCommentReport', 'Comment Report in last 14 days', ['range' => 14,'filter' => Input::get('filter'), 'name' => Input::get('name'), 'id' => Input::get('id') ], null) !!}
                </li>
                <li>
                    {!! link_to_action('ReportController@showCommentReport', 'Comment Report in last 30 days', ['range' => 30,'filter' => Input::get('filter'), 'name' => Input::get('name'), 'id' => Input::get('id')  ], null) !!}
                </li>
                <li>
                    {!! link_to_action('ReportController@showCommentReport', 'Custom', [
                    'dstart' => \Carbon\Carbon::parse(Input::get('range',7).' days ago')->toDateString(),
                    'dend'   => \Carbon\Carbon::now()->toDateString(),
                    'filter' => Input::get('filter'), 'name' => Input::get('name'), 'id' => Input::get('id')  ],null) !!}
                </li>
            </ul>
            @if(!isset($range))
                {!! Form::text('dstart', Input::get('dstart'),
                    ['placeholder'=>"Search From", 'class'=>"form-control date-input", 'id' => 'js-datepicker-sdate']) !!}
                {!! Form::text('dend', Input::get('dend'),
                    ['placeholder'=>"To", 'class'=>"form-control date-input js-datepicker", 'id' => 'js-datepicker-edate']) !!}
            @else
                {!! Form::hidden('range', Input::get('range')) !!}
            @endif
        </div>
        <div class="form-group has-feedback">
            {!! Form::select('filter',[
                'all'     => 'Show All',
                'pm'      => 'Show Internal PM',
                'expert'  => 'Show Expert',
                'creator' => 'Show Creator',
            ], Input::get('filter'), ['class'=>'form-control']) !!}
            {!! Form::text('name', Input::get('name'), ['placeholder'=>"Search by user name", 'id'=>'search-name', 'class'=>"form-control", 'onkeydown'=>'document.getElementById("search-id").value=""']) !!}
            {!! Form::text('id', Input::get('id'), ['placeholder'=>"Search by user ID", 'id'=>'search-id', 'class'=>"form-control", 'onkeydown'=>'document.getElementById("search-name").value=""']) !!}
        </div>
        {!! Form::submit('Go!', $attributes=["class"=>"btn btn-default js-btn-search", "type"=>"button"]) !!}
        {!! Form::close() !!}
        {{--</div>--}}
    </div>
    <div class="row text-center">
        <h4>
            {!! $users->total() !!} {!! $users->total() > 1 ? 'results' : 'result' !!},
            {!! $users->senderCount !!} {!! $users->senderCount > 1 ? 'senders' : 'sender' !!}, and 
            {!! $users->commentSum !!} {!! $users->commentSum > 1 ? 'comments' : 'comment' !!} are found.
        </h4>
    </div>
    <div class="row">
        <div class="col-md-12">
            <table class="table table-striped">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Role</th>
                    <th>{!! isset($range)? "Last $range days comment":"Custom interval comment" !!}</th>
                </tr>

                @foreach($users as $user)
                    <tr>
                        <td>{!! $user->user_id !!}</td>
                        <td>
                            <a href="{!! $user->textFrontLink() !!}" target="_blank">
                                {{ $user->fullName }}</a>
                        </td>
                        @if($is_super_admin && $user->isHWTrekPM())
                            <td>{!! $user->textHWTrekPM() !!}({!! $user->textType() !!})</td>
                        @else
                            <td>{!! ($user->isToBeExpert() && $user->isCreator())?'<font color="red">To Be Expert</font>':$user->textType()  !!}</td>
                        @endif
                        <td><span style="font-size:1.3em">{!! $user->commentCount !!}</span> <span> of total {!! $user->totalCommentCount !!}.</span></td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
    <div class="text-center">
        {!! $users->appends(Input::all())->render() !!}
    </div>

@stop

