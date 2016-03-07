@extends('layouts.master')
@include('layouts.macro')
@section('jqui')
    @include('layouts.jqui')
@stop
@section('css')
    <link rel="stylesheet" href="/css/user-list.css">
@stop
@section('js')
    <script src='/js/list.js'></script>
    <script src='/js/event-questionnaire.js'></script>
@stop

@section('content')
    <div class="page-header">
        <h1>{{ $title }}</h1>
    </div>

    @include ('report.questionnaires.questionnaire-search')

    <div class="row text-center">
        <h4>
            Total {{ $approve_event_users->total() }} participants SZ {{ $approve_event_users->shenzhen_count }} peoples |
            BJ {{ $approve_event_users->beijing_count }} peoples | TPE {{ $approve_event_users->taipei_count }} peoples
            <br><br>
            Dinner {{ $approve_event_users->dinner_count }} | Plus One {{ $approve_event_users->join_count }} |
            Sent Presentation {{ $approve_event_users->material_count }} | Bring Prototype {{ $approve_event_users->prototype_count }} |
            WeChat Contact {{ $approve_event_users->wechat_count }}

        </h4>
    </div>
    <div class="row">
        <div class="col-md-12">
            <table class="table table-striped">
                <tr>
                    <th>#</th>
                    <th class="table--width-limit">
                        Name
                    </th>
                    <th class="table--width-limit">
                        Company<br/>
                        <span class="table--text-light">Position</span>
                    </th>
                    <th>Locations</th>
                    <th class="table--width-limit">
                        SZ Flight<br/>
                        <span class="table--text-light">Arrival Time</span>
                    </th>
                    <th class="table--width-limit">
                        SZ to BJ Flight<br/>
                        <span class="table--text-light">Arrival Time</span>
                    </th>
                    <th class="table--width-limit">
                        BJ to TPE Flight<br/>
                        <span class="table--text-light">Arrival Time</span>
                    </th>
                    <th class="table--width-limit">
                        Dinner<br/>
                        <span class="table--text-light">Plus One</span>
                    </th>
                    <th class="table--width-limit">
                        Presentation<br/>
                        <span class="table--text-light">Prototype</span>
                    </th>
                    <th>Note</th>
                </tr>

                @foreach($approve_event_users as $approve_user)
                    <tr>
                        <td>{!! $approve_user->user_id !!}</td>

                        <td class="table--width-limit">
                            @if($approve_user->questionnaire)
                                <a href="{!! $approve_user->user->textFrontLink() !!}" target="_blank">
                                    {{ $approve_user->user->textFullName() }}</a>
                                @if($approve_user->project)
                                    | <a href="{!! $approve_user->project->textFrontProjectLink() !!}" target="_blank">{{ $approve_user->project->project_id }}</a>
                                @endif
                                <i style="cursor:pointer" class="fa fa-sticky-note-o" rel="{{ $approve_user->questionnaire->detail }}"></i>
                            @else
                                <a href="{!! $approve_user->user->textFrontLink() !!}" target="_blank">
                                    {{ $approve_user->user->textFullName() }}</a>
                                @if($approve_user->project)
                                    | <a href="{!! $approve_user->project->textFrontProjectLink() !!}" target="_blank">{{ $approve_user->project->project_id }}</a>
                                @endif
                                <i style="cursor:pointer" class="fa fa-sticky-note-o" rel=""></i>
                            @endif
                        </td>

                        <td class="table--width-limit">
                            @if($approve_user->questionnaire)
                                {{ $approve_user->questionnaire->company_name }}<br/>
                                <span class="table--text-light">{{ $approve_user->questionnaire->job_title  }}</span>
                            @else
                                {{ $approve_user->company }}<br/>
                                <span class="table--text-light">{{ $approve_user->job_title  }}</span>
                            @endif
                        </td>

                        <td>
                            @if($approve_user->questionnaire)
                                @foreach($approve_user->questionnaire->trip_participation as $participation)
                                {{ $participation }}<br/>
                                @endforeach
                            @endif
                        </td>

                        <td class="table--width-limit">
                            @if($approve_user->questionnaire)
                                {{ $approve_user->questionnaire->flight_local_to_shenzhen_flight }}<br/>
                                <span class="table--text-light">{{ $approve_user->questionnaire->flight_local_to_shenzhen_datetime }}</span>
                            @endif
                        </td>

                        <td class="table--width-limit">
                            @if($approve_user->questionnaire)
                                {{ $approve_user->questionnaire->flight_shenzhen_to_beijing_flight }}<br/>
                                <span class="table--text-light">{{ $approve_user->questionnaire->flight_shenzhen_to_beijing_datetime }}</span>
                            @endif
                        </td>

                        <td class="table--width-limit">
                            @if($approve_user->questionnaire)
                                {{ $approve_user->questionnaire->flight_beijing_to_taipei_flight }}<br/>
                                <span class="table--text-light">{{ $approve_user->questionnaire->flight_beijing_to_taipei_datetime }}</span>
                            @endif
                        </td>

                        <td>
                            @if($approve_user->questionnaire)
                                {{ $approve_user->questionnaire->attend_to_april_dinner == '1' ? 'Yes' : '' }}<br/>
                                <span class="table--text-light">
                                {{ $approve_user->questionnaire->other_member_to_join == '1' ? 'Yes' : '' }}
                            </span>
                            @endif
                        </td>

                        <td>
                            @if($approve_user->questionnaire)
                                {{ $approve_user->questionnaire->forward_material == '1' ? 'Yes' : '' }}<br/>
                                <span class="table--text-light">
                                {{ $approve_user->questionnaire->bring_prototype == '1' ? 'Yes' : '' }}
                                </span>
                            @endif
                        </td>
                        <td>
                            @if(!empty($approve_user->note))
                                <a href="javascript:void(0)"
                                   class="note" rel="{!! $approve_user->id !!}" note="{{ $approve_user->note }}">
                                    <i class="fa fa-pencil"></i>
                                    {{ mb_strimwidth($approve_user->note, 0, 130, mb_substr($approve_user->note, 0, 130) . '...') }}
                                </a>
                            @else
                                <div class="process-btns">
                                    <a href="javascript:void(0)"
                                       class="btn-mini btn-danger note" rel="{!! $approve_user->id !!}" note="{{ $approve_user->note }}" >
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
        {!! $approve_event_users->appends(Input::all())->render() !!}
    </div>
    <div id="questionnaire-2016-q1" class="ui-widget" title="Detail information" style="display:none">
        <table class="table table-striped">
            <tr>
                <td>Phone</td>
                <td><span id="phone"></span></td>
            </tr>
            <tr>
                <td>Wechat</td>
                <td><span id="wechat"></span></td>
            </tr>
        </table>
    </div>
    @include ('report.event-note-dialog')
@stop
