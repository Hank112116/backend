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
            Total {{ $questionnaires->total() }} peoples | SZ {{ $questionnaires->shenzhen_count }} peoples |
            BJ {{ $questionnaires->beijing_count }} peoples | TW {{ $questionnaires->taipei_count }} peoples
            <br><br>
            {{ $questionnaires->dinner_count }} Attend Dinner | {{ $questionnaires->prototype_count }} Prototype |
            {{ $questionnaires->join_count }} Other Join | {{ $questionnaires->wechat_count }} Have WeChat |
            {{ $questionnaires->material_count }} Forward material
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
                    <th>Participation</th>
                    <th class="table--width-limit">
                        At SZ<br/>
                        <span class="table--text-light">Arrival Time</span>
                    </th>
                    <th class="table--width-limit">
                        SZ to BJ<br/>
                        <span class="table--text-light">Arrival Time</span>
                    </th>
                    <th class="table--width-limit">
                        BJ to TW<br/>
                        <span class="table--text-light">Arrival Time</span>
                    </th>
                    <th>Dinner</th>
                    <th>Prototype</th>
                </tr>

                @foreach($questionnaires as $no => $questionnaire)
                    <tr>
                        <td>{!! $questionnaire->user->user_id !!}</td>

                        <td class="table--width-limit">
                            <a href="{!! $questionnaire->user->textFrontLink() !!}" target="_blank">
                                {{ $questionnaire->user->textFullName() }}</a>
                            <i style="cursor:pointer" class="fa fa-sticky-note-o" rel="{{ $questionnaire->detail }}"></i>
                        </td>

                        <td class="table--width-limit">
                            {{ $questionnaire->company_name }}<br/>
                            <span class="table--text-light">{{ $questionnaire->job_title  }}</span>
                        </td>

                        <td>
                            @foreach($questionnaire->trip_participation as $participation)
                            {{ $participation }}<br/>
                            @endforeach
                        </td>

                        <td class="table--width-limit">
                            {{ $questionnaire->flight_local_to_shenzhen_flight }}<br/>
                            <span class="table--text-light">{{ $questionnaire->flight_local_to_shenzhen_datetime }}</span>
                        </td>

                        <td class="table--width-limit">
                            {{ $questionnaire->flight_shenzhen_to_beijing_flight }}<br/>
                            <span class="table--text-light">{{ $questionnaire->flight_shenzhen_to_beijing_datetime }}</span>
                        </td>

                        <td class="table--width-limit">
                            {{ $questionnaire->flight_beijing_to_taipei_flight }}<br/>
                            <span class="table--text-light">{{ $questionnaire->flight_beijing_to_taipei_datetime }}</span>
                        </td>

                        <td>
                            {{ $questionnaire->attend_to_april_dinner == '1' ? 'Yes' : 'No' }}<br/>
                        </td>

                        <td>
                            {{ $questionnaire->bring_prototype == '1' ? 'Yes' : 'No' }}<br/>
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
    <div class="text-center">
        {!! $questionnaires->appends(Input::all())->render() !!}
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
            <tr>
                <td>Other Join</td>
                <td><span id="join"></span></td>
            </tr>
            <tr>
                <td>Forward Material</td>
                <td><span id="material"></span></td>
            </tr>
        </table>
    </div>
@stop

