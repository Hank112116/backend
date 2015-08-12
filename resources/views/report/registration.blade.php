@extends('layouts.master')
@include('layouts.macro')

@section('css')
    <link rel="stylesheet" href="/css/user-list.css">
@stop
@section('js')
    <script src='/js/list.js'></script>
@stop


@section('content')
    <div class="page-header">
        <h1>{{ isset($title) ?$title: 'Registration Report' }}</h1>
    </div>

    <div class="text-center">
        {{--<div class="col-md-12">--}}
        {!! Form::open(['action' => ['ReportController@showRegistrationReport', 'date'], 'method' => 'GET', 'class' => 'form-inline']) !!}
        <div class="form-group has-feedback">
            {!! Form::text('dstart', Input::get('dstart'),
                ['placeholder'=>"Search From", 'class'=>"form-control date-input", 'id' => 'js-datepicker-sdate']) !!}
            {!! Form::text('dend', Input::get('dend'),
                ['placeholder'=>"To", 'class'=>"form-control date-input js-datepicker", 'id' => 'js-datepicker-edate']) !!}
            <div class="radio">
                <label>
                    {!! Form::radio('filter', 'creator', Input::get('filter')=='creator' ) !!} Show Creator
                </label>
            </div>
            <div class="radio">
                <label>
                    {!! Form::radio('filter', 'expert', Input::get('filter')=='expert' ) !!} Show Expert
                </label>
            </div>
            <div class="radio">
                <label>
                    {!! Form::radio('filter', 'pm', Input::get('filter')=='pm' ) !!} Show Internal PM
                </label>
            </div>
            <div class="radio">
                <label>
                    {!! Form::radio('filter', 'all', Input::get('filter')=='all'||Input::get('filter')==null ) !!} Show All
                </label>
            </div>
        </div>
        {!! Form::submit('Go!',$attributes=["class"=>"btn btn-default js-btn-search","type"=>"button"]) !!}
        {!! Form::close() !!}
        {{--</div>--}}
    </div>
    {!! link_to_action('ReportController@showRegistrationReport', 'Register in last week',
        ['dstart' => \Carbon\Carbon::parse('last week')->toDateString(),
         'dend'   => \Carbon\Carbon::now()->toDateString()
        ],null) !!}
    <br>
    {!! link_to_action('ReportController@showRegistrationReport', 'Register in last month',
        ['dstart' => \Carbon\Carbon::parse('last month')->toDateString(),
         'dend'   => \Carbon\Carbon::now()->toDateString()
        ],null) !!}
    <br>
    {!! link_to_action('ReportController@showRegistrationReport', 'Register in last year',
        ['dstart' => \Carbon\Carbon::parse('last year')->toDateString(),
         'dend'   => \Carbon\Carbon::now()->toDateString()
        ],null) !!}
    <div class="row">
        <div class="col-md-12">
            <table class="table table-striped">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Role</th>
                    @if(Auth::user()->isManagerHead() || Auth::user()->isAdmin())
                        <th class="table--user-mail">EMail</th>
                    @endif
                    <th>Country<br/>City</th>
                    <th class="table--width-limit">
                        Company<br/>
                        <span class="table--text-light">Position</span>
                    </th>
                    <th>
                        Registed On
                        @if(!$is_restricted)
                            <br/><span class="table--text-light">Signup Ip</span>
                        @endif
                    </th>
                    <th>EMail<br/>Verify</th>
                    <th>Active</th>
                    <th></th>
                </tr>

                @foreach($users as $user)
                    <tr>
                        <td>{!! $user->user_id !!}</td>
                        <td>
                            <a href="{!! $user->textFrontLink() !!}" target="_blank">
                                {{ $user->textFullName() }}</a>
                        </td>
                        @if(Auth::user()->isAdmin())
                            <td>{!! $user->textHWTrekPM() !!}<br>
                                @if(!$user->isHWTrekPM())
                                    <span class="user-sub-category">
                                        {!! $user->textType() !!}

                                        {{--<a class="change_pm" href="#" rel="{!! $user->user_id !!}"> To PM </a>--}}
                                    </span>
                                @else
                                    <span class="user-sub-category">
                                        {{--<a class="change_user" href="#" rel="{!! $user->user_id !!}"> To User </a>--}}
                                    </span>
                                @endif
                            </td>
                        @else
                            <td>{!! $user->textType() !!}</td>
                        @endif


                        @if(Auth::user()->isManagerHead() || Auth::user()->isAdmin())
                            <td class="table--user-mail">
                                {{ $user->email }}
                                @if('facebook' === $user->social)
                                    <i class="fa fa-facebook-square"></i>
                                @elseif('linkedin' === $user->social)
                                    <i class="fa fa-linkedin-square"></i>
                                @endif
                            </td>
                        @endif
                        <td>{{ $user->country }}<br/>{{ $user->city }}</td>

                        <td class="table--width-limit">
                            {{ $user->company }}<br/>
                            <span class="table--text-light">{{ $user->business_id  }}</span>
                        </td>

                        <td>
                            <span data-time="{!! $user->date_added !!}">{!! HTML::date($user->date_added) !!}</span>
                            @if(!$is_restricted)
                                <br/><span class="table--text-light">{!! $user->signup_ip !!}</span>
                            @endif
                        </td>

                        <td>{!! $user->textEmailVerify() !!}</td>
                        <td>{!! $user->textActive() !!}</td>

                        <td>
                            {!! link_to_action(
                                    'UserController@showDetail', 'DETAIL',
                                    $user->user_id, ['class' => 'btn-mini']) !!}

                            @if(!$is_restricted and !$user->isExpert() and $user->isToBeExpert())
                                {!! link_to_action(
                                        'UserController@showUpdate', 'EDIT & To-Expert',
                                        $user->user_id, ['class' => 'btn-mini btn-danger']) !!}
                            @else
                                {!! link_to_action(
                                        'UserController@showUpdate', 'EDIT',
                                        $user->user_id, ['class' => 'btn-mini']) !!}
                            @endif
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
    <div class="text-center">
        {!! $users->appends(Input::all())->render() !!}
    </div>

@stop

