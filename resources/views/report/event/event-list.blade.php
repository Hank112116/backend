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
    <div class="page-header"></div>
    @include ('report.event.event-time-search')
    @include ('report.event.event-summary')
    @include ('report.event.event-search')
    <div class="row">
        <div class="col-md-12">
            <table class="table table-striped float-thead">
                <thead>
                <tr>
                    <th>U.ID</th>
                    <th>Attendee</th>
                    <th>Role</th>
                    <th class="table--width-limit">
                        Company<br/>
                        <span class="table--text-light">Position</span>
                    </th>
                    <th>Established Since</th>
                    <th class="table--width-limit">
                        Project<br/>
                        <span class="table--text-light">Category</span>
                    </th>
                    <th class="table--width-limit">
                        Internal Selection<br/>
                        <span class="table--text-light">By</span>
                    </th>
                    <th class="table--width-limit">
                        Assigned PM<br/>
                        <span class="table--text-light">Follow PM</span>
                    </th>
                    <th>Ticket Type</th>
                    <th>
                        Contact Email
                    </th>
                    <th class="table--width-limit">
                        Country<br/>
                        <span class="table--text-light">City</span>
                    </th>
                    <th class="table--width-limit">
                        Apply Time
                    </th>
                    <th>Note</th>
                </tr>
                </thead>
                <tbody>
                @foreach($event_users as $event_user)
                    <tr>
                        <td>
                            @if($event_user->isDropped())
                                <i class="fa fa-thumbs-o-down fa-2x" title="Incomplete"></i>
                            @endif
                            @if($event_user->user)
                            <a href="{!! $event_user->user->textFrontLink() !!}" target="_blank">
                                {!! $event_user->user->user_id !!}
                            </a>
                            @endif
                        </td>

                        <td>
                            @if($event_user->isDropped())
                                @if($event_user->user)
                                    {{ $event_user->user->textFullName() }}
                                @endif
                            @else
                                {{ $event_user->textFullName() }}
                                @if(!$event_user->isCoincide())
                                    <i class="fa fa-pencil-square-o" title="Different from profile."></i>
                                @endif
                                @if(Auth::user()->isManagerHead() || Auth::user()->isAdmin())
                                    <br>
                                    @if($event_user->isTour())
                                        @if(!$event_user->approved_at)
                                            <span class="user-sub-category">
                                            <input type="checkbox"  class="approve_event_user" rel="{!! $event_user->user_id !!}" title="approve event user"> Select
                                        </span>
                                        @else
                                            <label for="active_1" class='iradio-lable'>Selected</label>
                                        @endif
                                    @endif
                                @endif
                            @endif
                        </td>

                        <td>
                        @if($event_user->user)
                            {{ $event_user->user->textType() }}
                            @if($event_user->getMessage())
                                <li style="cursor:pointer" class="fa fa-commenting-o fa-fw fa-2x" rel="{!! $event_user->getMessage() !!}"></li>
                            @endif
                        @endif
                        </td>

                        <td class="table--width-limit">
                            {{ $event_user->company }}
                            <br/>
                            <span class="table--text-light">{{ $event_user->job_title  }}</span>
                        </td>
                        <td>
                            @if($event_user->getEstablishedSince())
                                {{ $event_user->getEstablishedSince() }}
                            @endif
                        </td>

                        <td>
                            @if($event_user->project)
                                <a href="{!! $event_user->project->textFrontProjectLink() !!}" target="_blank">{{ $event_user->project->project_id }}.{{ $event_user->project->textTitle() }}</a><br>
                                <span class="table--text-light">{{ $event_user->project->textCategory() }}</span>
                            @endif
                        </td>

                        <td>
                            @if(!$event_user->isDropped())
                                @if($event_user->getInternalSetStatus())
                                    <a href="javascript:void(0)"
                                       class="internal-selection" rel="{!! $event_user->id !!}" status="{{ $event_user->getInternalSetStatus() }}" tour="{{ $event_user->isTour() }}">
                                        <i class="fa fa-pencil"></i>
                                        {{ $event_user->getTextInternalSetStatus() }}
                                    </a><br/>
                                    <span class="table--text-light">{{ $event_user->getInternalSetStatusOperator() }}</span><br/>
                                    <span class="table--text-light">{{ $event_user->getInternalSetStatusUpdatedAt() }}</span>
                                @else
                                    <div class="process-btns">
                                        <a href="javascript:void(0)"
                                           class="btn-mini btn-danger internal-selection" rel="{!! $event_user->id !!}" status="{{ $event_user->getInternalSetStatus() }}" tour="{{ $event_user->isTour() }}">
                                            <i class="fa fa-pencil fa-fw"></i>ADD</a>
                                    </div>
                                @endif
                            @endif
                        </td>
                        <td>
                            @if($event_user->project)
                                @if(!$event_user->project->hasProjectManager())
                                    <p class="hub-manages">
                                        <i class="fa fa-fw fa-exclamation-triangle"></i> No PM
                                    </p>
                                @else
                                    @foreach($event_user->project->getHubManagerNames() as $manager)
                                        <p class="hub-manages">
                                            <i class="fa fa-user fa-fw"></i> {!! $manager !!}
                                        </p>
                                    @endforeach
                                @endif
                            @endif

                            @if($event_user->getFollowPM())
                                <a href="javascript:void(0)"
                                   class="follow-pm" rel="{!! $event_user->id !!}" pm="{{ $event_user->getFollowPM() }}">
                                    <i class="fa fa-pencil"></i>
                                    {{ $event_user->getFollowPM() }}
                                </a>
                            @else
                                <div class="process-btns">
                                    <button class="btn-main btn-flat-purple follow-pm" rel="{!! $event_user->id !!}" pm="{{ $event_user->getFollowPM() }}" >
                                        <i class="fa fa-pencil fa-fw"></i>Follow Up</button>
                                </div>
                            @endif
                        </td>
                        <td style="width: 130px">
                            {!! $event_user->getTextTicketType() !!} <br/>
                            @if ($event_user->hasGuestJoin())
                            <i style="cursor:pointer" class="fa fa-user-plus fa-2x" title="Has Guest" rel="{{ json_encode($event_user->getGuestInfo()) }}"></i>
                            @endif
                        </td>
                        <td class="table--user-mail">
                            @if(!$event_user->isDropped())
                                {{ $event_user->email }}
                            @endif
                        </td>
                        <td class="table--width-limit">
                            @if(!$event_user->isDropped())
                                @if($event_user->user)
                                {{ $event_user->user->country }}<br/>
                                <span class="table--text-light">{{ $event_user->user->city }}</span>
                                @endif
                            @endif
                        </td>
                        <td class="table--width-limit">
                            {{ $event_user->textApplyTime() }}
                        </td>

                        <td>
                            @if(!empty($event_user->getNote()))
                                <a href="javascript:void(0)"
                                   class="note" rel="{!! $event_user->id !!}" note="{{ $event_user->getNote() }}">
                                    <i class="fa fa-pencil"></i>
                                    {{ mb_strimwidth($event_user->getNote(), 0, 130, mb_substr($event_user->getNote(), 0, 130) . '...') }}
                                </a>
                                <br/>
                                <span class="table--text-light">{{ $event_user->getNoteOperator() }}</span><br/>
                                <span class="table--text-light">{{ $event_user->getNoteUpdatedAt() }}</span>
                            @else
                            <div class="process-btns">
                                <a href="javascript:void(0)"
                                   class="btn-mini btn-danger note" rel="{!! $event_user->id !!}" note="{{ $event_user->getNote() }}" >
                                    <i class="fa fa-pencil fa-fw"></i>NOTE</a>
                            </div>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="text-center">
            @if ($event_users->total() == 0)
                <h5>No records yet!</h5>
            @endif
        </div>
    </div>
    <div class="text-center">
        {!! $event_users->appends(Input::all())->render() !!}
    </div>
    <input type="hidden" id="event_id" name="event_id" value="{{ $event_id }}">
    <div id="dialog" class="ui-widget" title="Apply messages" style="display:none"></div>
    @include ('report.dialog.event-note')
    @include ('report.dialog.event-questionnaire')
    @include ('report.dialog.event-internal-selection-tour')
    @include ('report.dialog.event-internal-selection-meetup')
    @include ('report.dialog.event-follow-pm')
    @include ('report.dialog.event-guest-info')
@stop

