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
    <script src="{{ LinkGen::assets('js/event-questionnaire.js') }}"></script>
@stop

@section('content')
    <div class="page-header"></div>

    @include ('report.questionnaires.questionnaire-search')

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
                    <th>
                        Form Status<br/>
                        <span class="table--text-light">PM Mark</span>
                    </th>
                    <th class="table--width-limit">
                        Assigned PM<br/>
                        <span class="table--text-light">Follow PM</span>
                    </th>
                    <th>Contact Details</th>
                    <th>Attendance</th>
                    <th>Itinerary</th>
                    <th>Materials</th>
                    <th>Video URL</th>
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

                        <td style="max-width: 200px">
                            @if($approve_user->questionnaire)
                                {{ $approve_user->questionnaire->company_name }}<br/>
                                <span class="table--text-light">{{ $approve_user->questionnaire->job_title }}</span>
                            @endif
                        </td>

                        <td>
                            @if($approve_user->questionnaire)

                                @if($approve_user->questionnaire->form_status == 'Ongoing')
                                    In Progress
                                    <br/>
                                    @if($approve_user->getInternalSetFormStatus())
                                        <a href="javascript:void(0)"
                                           class="pm-mark-form-status-selection" rel="{!! $approve_user->id !!}" status="{{ $approve_user->getInternalSetFormStatus() }}">
                                            <i class="fa fa-pencil"></i>
                                            {{ $approve_user->getInternalSetFormStatus() }}
                                        </a><br/>
                                        <span class="table--text-light">{{ $approve_user->getInternalSetFormStatusOperator() }}</span><br/>
                                        <span class="table--text-light">{{ $approve_user->getInternalSetFormStatusUpdatedAt() }}</span>
                                    @else
                                        <div class="process-btns">
                                            <button class="btn-main btn-flat-purple pm-mark-form-status-selection" rel="{!! $approve_user->id !!}" status="{{ $approve_user->getInternalSetFormStatus() }}" >
                                                <i class="fa fa-pencil fa-fw"></i>Mark Status</button>
                                        </div>
                                    @endif
                                @else
                                    {{ $approve_user->questionnaire->form_status }}
                                @endif

                            @else
                                Initial<br/>
                                @if($approve_user->getInternalSetFormStatus())
                                    <a href="javascript:void(0)"
                                       class="pm-mark-form-status-selection" rel="{!! $approve_user->id !!}" status="{{ $approve_user->getInternalSetFormStatus() }}">
                                        <i class="fa fa-pencil"></i>
                                        {{ $approve_user->getInternalSetFormStatus() }}
                                    </a><br/>
                                    <span class="table--text-light">{{ $approve_user->getInternalSetFormStatusOperator() }}</span><br/>
                                    <span class="table--text-light">{{ $approve_user->getInternalSetFormStatusUpdatedAt() }}</span>
                                @else
                                    <div class="process-btns">
                                        <button class="btn-main btn-flat-purple pm-mark-form-status-selection" rel="{!! $approve_user->id !!}" status="{{ $approve_user->getInternalSetFormStatus() }}" >
                                            <i class="fa fa-pencil fa-fw"></i>Mark Status</button>
                                    </div>
                                @endif
                            @endif
                        </td>

                        <td>
                            @if($approve_user->project)
                                @if(!$approve_user->project->hasProjectManager())
                                    <p class="hub-manages">
                                        <i class="fa fa-fw fa-exclamation-triangle"></i> No PM
                                    </p>
                                @else
                                    @foreach($approve_user->project->getHubManagerNames() as $manager)
                                        <p class="hub-manages">
                                            <i class="fa fa-user fa-fw"></i> {!! $manager !!}
                                        </p>
                                    @endforeach
                                @endif
                            @endif

                            @if($approve_user->getFollowPM())
                                <a href="javascript:void(0)"
                                   class="follow-pm" rel="{!! $approve_user->id !!}" pm="{{ $approve_user->getFollowPM() }}">
                                    <i class="fa fa-pencil"></i>
                                    {{ $approve_user->getFollowPM() }}
                                </a>
                            @else
                                <div class="process-btns">
                                    <button class="btn-main btn-flat-purple follow-pm" rel="{!! $approve_user->id !!}" pm="{{ $approve_user->getFollowPM() }}" >
                                        <i class="fa fa-pencil fa-fw"></i>Follow Up</button>
                                </div>
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
                                            Dinner
                                        @endif
                                        @if($trip_participation == 'shenzhen')
                                            Shenzhen
                                        @endif
                                        @if($trip_participation == 'shanghai')
                                            Shanghai
                                        @endif
                                        <br/>
                                    @endforeach
                                @endif

                            @endif
                        </td>
                        <td style="min-width: 300px">
                            @if($approve_user->questionnaire)
                                @if($approve_user->questionnaire->shenzhen_flight_data)
                                    Shenzhen:
                                    {{ $approve_user->questionnaire->shenzhen_flight_data }}
                                    <br/>
                                @endif

                                @if($approve_user->questionnaire->shanghai_flight_data)
                                    Shanghai:
                                    {{ $approve_user->questionnaire->shanghai_flight_data }}
                                    <br/>
                                @endif
                            @endif
                        </td>
                        <td>
                            @if($approve_user->questionnaire)
                                @if($approve_user->questionnaire->company_logo)
                                    - {!! link_to($approve_user->questionnaire->company_logo[0]['key'], 'Company Logo', ['target' => '_blank']) !!}<br/>
                                @endif
                                @if($approve_user->questionnaire->attachments)
                                    @foreach($approve_user->questionnaire->attachments as $attachment)
                                        - {!! link_to($attachment['key'], str_limit($attachment['name'], 25), ['target' => '_blank', 'title' => $attachment['name']]) !!}<br/>
                                    @endforeach
                                @endif
                            @endif
                        </td>

                        <td>
                            @if($approve_user->questionnaire)
                                @if($approve_user->questionnaire->video_url)
                                    @foreach($approve_user->questionnaire->video_url as $key => $url)
                                        @if($url)
                                            - {!! link_to($url, 'Video No.' . ($key + 1) , ['target' => '_blank']) !!}<br/>
                                        @endif
                                    @endforeach
                                @endif
                            @endif
                        </td>

                        <td>
                            @if(!empty($approve_user->getNote()))
                                <a href="javascript:void(0)"
                                   class="note" rel="{!! $approve_user->id !!}" note="{{ $approve_user->getNote() }}">
                                    <i class="fa fa-pencil"></i>
                                    {{ str_limit($approve_user->getNote(), 130) }}
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
    @include ('report.dialog.event-follow-pm')
    @include ('report.dialog.event-pm-mark-form-status')
@stop
