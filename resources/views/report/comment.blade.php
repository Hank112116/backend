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
        <h1>{{ isset($title) ?$title: 'Comment Summary' }}</h1>
    </div>

    <div class="row text-center search-bar">
        {{--<div class="col-md-12">--}}
        {!! Form::open(['action' => ['ReportController@showCommentReport', 'date'], 'method' => 'GET', 'class' => 'form-inline']) !!}
        <div class="form-group has-feedback">
            <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown">Time range
                <span class="caret"></span></button>
            <ul class="dropdown-menu">
                <li>
                    {!! link_to_action('ReportController@showCommentReport', 'Comment Report in last 7 days', ['range' => 7,'filter' => Input::get('filter')], null) !!}
                </li>
                <li>
                    {!! link_to_action('ReportController@showCommentReport', 'Comment Report in last 14 days', ['range' => 14,'filter' => Input::get('filter')], null) !!}
                </li>
                <li>
                    {!! link_to_action('ReportController@showCommentReport', 'Comment Report in last 30 days', ['range' => 30,'filter' => Input::get('filter')], null) !!}
                </li>
                <li>
                    {!! link_to_action('ReportController@showCommentReport', 'Custom', [
                    'dstart' => \Carbon\Carbon::parse(Input::get('range',7).' days ago')->toDateString(),
                    'dend'   => \Carbon\Carbon::now()->toDateString()],null) !!}
                </li>
            </ul>
            @if(!isset($range))
                {!! Form::text('dstart', Input::get('dstart'),
                    ['placeholder'=>"Search From", 'class'=>"form-control date-input", 'id' => 'js-datepicker-sdate']) !!}
                {!! Form::text('dend', Input::get('dend'),
                    ['placeholder'=>"To", 'class'=>"form-control date-input js-datepicker", 'id' => 'js-datepicker-edate']) !!}
            @else
                {!! $range !!}
                {!! Form::hidden('range',Input::get('range')) !!}
            @endif
        </div>
        <div class="form-group has-feedback">
            @if(!$is_restricted)
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
                        {!! Form::radio('filter', 'pm', Input::get('filter')=='pm') !!} Show InternalPM
                    </label>
                </div>
                <div class="radio">
                    <label>
                        {!! Form::radio('filter', 'all', Input::get('filter')=='all'||Input::get('filter')==null ) !!} Show All
                    </label>
                </div>
            @endif
            <br>
            {!! Form::text('name', Input::get('name'), ['placeholder'=>"Search by user name", 'class'=>"form-control"]) !!}
        </div>
        @if(!$is_restricted||!isset($range))
            {!! Form::submit('Go!',$attributes=["class"=>"btn btn-default js-btn-search","type"=>"button"]) !!}
        @endif
        {!! Form::close() !!}
        {{--</div>--}}
    </div>

    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-6">
            <table class="table table-striped">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Role</th>
                    <th>Sent</th>
                    <th>Received</th>
                    <th>Total</th>
                </tr>

                @foreach($users as $user)
                    <tr>
                        <td>{!! $user->user_id !!}</td>
                        <td>
                            <a href="{!! $user->textFrontLink() !!}" target="_blank">
                                {{ $user->fullName }}</a>
                        </td>
                        @if(!$is_restricted&&$user->isHWTrekPM())
                            <td>{!! $user->textHWTrekPM() !!}({!! $user->textType() !!})</td>
                        @else
                            <td>{!! $user->textType() !!}</td>
                        @endif
                        <td>{!! $user->sendCommentCount !!}</td>
                        <td>{!! $user->receiveCommentCount !!}</td>
                        <td>{!! $user->sendCommentCount + $user->receiveCommentCount !!}</td>
                    </tr>
                @endforeach
            </table>
        </div>
        <div class="col-md-3"></div>
    </div>
    <div class="text-center">
        {!! $users->appends(Input::all())->render() !!}
    </div>

@stop

