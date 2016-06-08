@extends('layouts.master')
@include('layouts.macro')
@section('jqui')
    @include('layouts.jqui')
@stop
@section('css')
    <link rel="stylesheet" href="{{ LinkGen::assets('css/user-list.css') }}">
@stop
@section('js')
    <script src="{{ LinkGen::assets('js/list.js') }}"></script>
    <script src="{{ LinkGen::assets('js/event-report-list.js') }}"></script>
@stop

@section('content')
    <div class="page-header">
        <h1>{{ $title or 'Registration Report' }}</h1>
    </div>
    @include ('report.event-search')
    <div class="row text-center">
        <h4>
            Total {{ $event_users->total() }} applications | {{ $event_users->expert_count }} Experts | {{ $event_users->creator_count }} Creators |
            {{ $event_users->unique_expert_count }} Unique Experts | {{ $event_users->unique_creator_count }} Unique Creators
        </h4>
    </div>
    <div class="row">
        <div class="col-md-12">
            <table class="table table-striped">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th class="table--width-limit">
                        Company<br/>
                        <span class="table--text-light">Position</span>
                    </th>
                    <th class="table--width-limit">
                        Country<br/>
                        <span class="table--text-light">City</span>
                    </th>
                    <th>Phone</th>
                    <th>WeChat</th>
                    <th>Project</th>
                    <th class="table--width-limit">
                        Apply Time(PST)<br/>
                        <span class="table--text-light">Member since</span>
                    </th>
                    <th>Note</th>
                </tr>

                @foreach($event_users as $event_user)
                    <tr>
                        <td>
                            @if($event_user->user)
                            {!! $event_user->user->user_id !!}<br>
                                <span class="table--text-light" title="Apply count.">[{{ $event_user->getApplyCount() }}]</span>
                            @endif
                        </td>

                        <td>
                            @if($event_user->user)
                                <a href="{!! $event_user->user->textFrontLink() !!}" target="_blank">
                                    {{ $event_user->textFullName() }}
                                </a>
                                @if(!$event_user->isCoincide())
                                    <font color="#ff8c00"><i class="fa fa-pencil-square-o" title="Different from profile."></i></font>
                                @endif

                                @if(Auth::user()->isManagerHead() || Auth::user()->isAdmin())
                                    <br>
                                    @if(!$event_user->approved_at)
                                    <span class="user-sub-category">
                                        <input type="checkbox"  class="approve_event_user" rel="{!! $event_user->user_id !!}"> Select
                                    </span>
                                    @else
                                        <label for="active_1" class='iradio-lable'>Selected</label>
                                    @endif
                                @endif
                            @else
                                {{ $event_user->textFullName() }}
                            @endif
                        </td>


                        <td class="table--user-mail">
                            {{ $event_user->email }}
                        </td>

                        <td>
                        @if($event_user->user)
                            {!! ($event_user->user->isToBeExpert() or $event_user->user->isApplyExpert())?"<font color='red'>{$event_user->user->textType()}</font>":$event_user->user->textType() !!}
                            @if($event_user->message)
                                <li style="cursor:pointer" class="fa fa-commenting-o fa-fw fa-2x" rel="{!! $event_user->message !!}"></li>
                            @endif
                        @endif
                        </td>

                        <td class="table--width-limit">
                            @if($event_user->company_url)
                                <a href="{!! $event_user->company_url !!}" target="_blank">{{ $event_user->company }}</a><br/>
                            @else
                                {{ $event_user->company }}<br/>
                            @endif
                            <span class="table--text-light">{{ $event_user->job_title  }}</span>
                        </td>
                        <td class="table--width-limit">
                            @if($event_user->user)
                            {{ $event_user->user->country }}<br/>
                            <span class="table--text-light">{{ $event_user->user->city }}</span>
                            @endif
                        </td>

                        <td>{!! $event_user->phone !!}</td>

                        <td>{!! $event_user->wechat !!}</td>

                        <td>
                        @if($event_user->project)
                            <a href="{!! $event_user->project->textFrontProjectLink() !!}" target="_blank">{{ $event_user->project->textTitle() }}</a><br>
                            <span class="table--text-light">{{ $event_user->project->textCategory() }}</span>
                        @endif
                        </td>

                        <td class="table--width-limit">
                            {{ $event_user->textApplyTime() }}<br/>
                            @if($event_user->user)
                            <span class="table--text-light">{{ $event_user->user->textRegistedOn() }}</span>
                            @endif
                        </td>

                        <td>
                            @if(!empty($event_user->note))
                                <a href="javascript:void(0)"
                                   class="note" rel="{!! $event_user->id !!}" note="{{ $event_user->note }}">
                                    <i class="fa fa-pencil"></i>
                                    {{ mb_strimwidth($event_user->note, 0, 130, mb_substr($event_user->note, 0, 130) . '...') }}
                                </a>
                            @else
                            <div class="process-btns">
                                <a href="javascript:void(0)"
                                   class="btn-mini btn-danger note" rel="{!! $event_user->id !!}" note="{{ $event_user->note }}" >
                                    <i class="fa fa-pencil fa-fw"></i>NOTE</a>
                            </div>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
    <div class="text-center">
        {!! $event_users->appends(Input::all())->render() !!}
    </div>
    <input type="hidden" id="event_id" name="event_id" value="{{ $event_id }}">
    <div id="dialog" class="ui-widget" title="Apply messages" style="display:none"></div>
    @include ('report.event-note-dialog')
    @include ('report.event-questionnaire-dialog')
@stop

