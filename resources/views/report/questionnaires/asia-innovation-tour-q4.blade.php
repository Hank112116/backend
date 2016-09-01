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
    <div class="page-header"></div>

    @include ('report.questionnaires.questionnaire-q1-search')

    <div class="row">
        <div class="col-md-12">
            <table class="table table-striped float-thead">
                <thead>
                    <tr>
                        <th>U.ID</th>
                        <th class="table--width-limit">
                            Attendee
                        </th>
                        <th class="table--width-limit">
                            Project<br/>
                            <span class="table--text-light">Category</span>
                        </th>
                        <th class="table--width-limit">
                            Company<br/>
                            <span class="table--text-light">Position</span>
                        </th>
                        <th>Form Status</th>
                        <th>Contact Details</th>
                        <th>Attendance & Itinerary</th>
                        <th>Materials</th>
                        <th>Note</th>
                    </tr>
                </thead>
                @foreach($approve_event_users as $approve_user)
                    <tr>
                        <td>{!! $approve_user->user_id !!}</td>

                        <td class="table--width-limit">
                            @if($approve_user->questionnaire)
                                <a href="{!! $approve_user->user->textFrontLink() !!}" target="_blank">
                                    @if($approve_user->questionnaire->attendee_name)
                                        {{ $approve_user->questionnaire->attendee_name }}
                                    @else
                                        {{ $approve_user->textFullName() }}
                                    @endif
                                </a>
                            @else
                                <a href="{!! $approve_user->user->textFrontLink() !!}" target="_blank">
                                    {{ $approve_user->textFullName() }}</a>
                            @endif
                        </td>

                        <td>
                            <a href="{!! $approve_user->project->textFrontProjectLink() !!}" target="_blank">{{ $approve_user->project->project_id }}</a><br/>
                            <span class="table--text-light">{{ $approve_user->project->textCategory() }}</span>

                            @if($approve_user->questionnaire)
                                @if(
                                    $approve_user->questionnaire->guest_attendee_name
                                    or $approve_user->questionnaire->guest_job_title
                                    or $approve_user->questionnaire->guest_email
                                    or $approve_user->questionnaire->guest_phone
                                    or $approve_user->questionnaire->guest_info
                                )
                                <br/><i style="cursor:pointer" class="fa fa-user-plus fa-2x" title="Has Guest" rel="{{ $approve_user->questionnaire->guest_json }}"></i>
                                @endif
                            @endif
                        </td>

                        <td>
                            @if($approve_user->questionnaire)
                                {{ $approve_user->questionnaire->company_name }}<br/>
                                <span class="table--text-light">{{ $approve_user->questionnaire->job_title }}</span>
                            @endif
                        </td>

                        <td>
                            @if($approve_user->questionnaire)
                                {{ $approve_user->questionnaire->form_status }}
                            @endif
                        </td>

                        <td>
                            @if($approve_user->questionnaire)
                                {{ $approve_user->questionnaire->email }}<br/>
                                <span class="table--text-light">{{ $approve_user->questionnaire->phone }}</span>
                            @endif
                        </td>

                        <td>
                            @if($approve_user->questionnaire)

                                @if($approve_user->questionnaire->trip_participation)
                                    @foreach($approve_user->questionnaire->trip_participation as $trip_participation)
                                        @if($trip_participation == 'dinner')
                                            Dinner:
                                            @if($approve_user->questionnaire->dinner_flight and $approve_user->questionnaire->dinner_datetime)
                                                {{ $approve_user->questionnaire->dinner_flight }} -
                                                {{ Carbon::parse($approve_user->questionnaire->dinner_datetime)->format('M jS g:ia') }} <br/>
                                            @else
                                                I haven't book one yet
                                            @endif

                                        @endif
                                        @if($trip_participation == 'shenzhen')
                                            Shenzhen:
                                            @if($approve_user->questionnaire->shenzhen_flight and $approve_user->questionnaire->shenzhen_datetime)
                                                {{ $approve_user->questionnaire->shenzhen_flight }} -
                                                {{ Carbon::parse($approve_user->questionnaire->shenzhen_datetime)->format('M jS g:ia') }} <br/>
                                            @else
                                                I haven't book one yet
                                            @endif

                                        @endif
                                        @if($trip_participation == 'kyoto')
                                            Kyoto:
                                            @if($approve_user->questionnaire->kyoto_flight and $approve_user->questionnaire->kyoto_datetime)
                                                {{ $approve_user->questionnaire->kyoto_flight }} -
                                                {{ Carbon::parse($approve_user->questionnaire->kyoto_datetime)->format('M jS g:ia') }} <br/>
                                            @else
                                                I haven't book one yet
                                            @endif

                                        @endif
                                        @if($trip_participation == 'osaka')
                                            Osaka:
                                            @if($approve_user->questionnaire->osaka_flight and $approve_user->questionnaire->osaka_datetime)
                                                {{ $approve_user->questionnaire->osaka_flight }} -
                                                {{ Carbon::parse($approve_user->questionnaire->osaka_datetime)->format('M jS g:ia') }} <br/>
                                            @else
                                                I haven't book one yet
                                            @endif

                                        @endif
                                    @endforeach
                                @endif

                            @endif
                        </td>

                        <td>
                            @if($approve_user->questionnaire)
                                @if($approve_user->questionnaire->company_logo)
                                    {!! link_to($approve_user->questionnaire->company_logo['key'], 'Company Logo', ['target' => '_blank']) !!}<br/>
                                @endif
                                @if($approve_user->questionnaire->attachments)
                                    @foreach($approve_user->questionnaire->attachments as $attachment)
                                            {!! link_to($attachment['key'], $attachment['name'], ['target' => '_blank']) !!}<br/>
                                    @endforeach
                                @endif
                            @endif
                        </td>

                        <td>
                            @if(!empty($approve_user->getNote()))
                                <a href="javascript:void(0)"
                                   class="note" rel="{!! $approve_user->id !!}" note="{{ $approve_user->getNote() }}">
                                    <i class="fa fa-pencil"></i>
                                    {{ mb_strimwidth($approve_user->getNote(), 0, 130, mb_substr($approve_user->getNote(), 0, 130) . '...') }}
                                </a>
                                <br/>
                                <span class="table--text-light">{{ $approve_user->getNoteOperator() }}</span><br/>
                                <span class="table--text-light">{{ $approve_user->getNoteUpdatedAt() }}</span>

                            @else
                                <div class="process-btns">
                                    <a href="javascript:void(0)"
                                       class="btn-mini btn-danger note" rel="{!! $approve_user->id !!}" note="{{ $approve_user->getNote() }}" >
                                        <i class="fa fa-pencil fa-fw"></i>NOTE</a>
                                </div>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
    @include ('report.dialog.event-note')
    @include ('report.dialog.event-questionnaire-guest')
@stop
