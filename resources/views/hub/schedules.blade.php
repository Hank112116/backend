@extends('layouts.master')
@section('jqui')
    @include('layouts.jqui')
@stop
@section('css')
    @cssLoader('hub-schedules')
@stop

@section('js')
    @jsLoader('hub-schedules')
@stop

@section('content')

<div class="page-header">
    <h1>Schedules</h1>
    <p class="page-header--notice">
        <i class="fa fa-smile-o fa-fw"></i>
        Login HWTrek Before
        <a class="btn-mini" href="#"><i class="fa fa-pencil fa-fw"></i>EDIT</a>
    </p>
</div>

<div class="row">
    <div class="col-md-12">

        <table class="table table-striped">
            <tr>
                <th>#</th>
                <th class="table--name">Project</th>
                <th>User</th>
                <th>Note</th>
                <th>Approve</th>
                <th>PM</th>
                <th></th>
                <th>Approve & Versions</th>
            </tr>

            @foreach($schedules as $s)
            <tr>
                <td>{!! $s->project_id !!}</td>
                <td class="table--name">
                    <a href="{!! $s->textFrontLink() !!}" target="_blank">{{ $s->project_title }}</a>
                </td>
                <td>
                    @if($s->user)
                    <a href="{!! $s->user->textFrontLink() !!}" target="_blank">{{ $s->user->textFullName() }}</a>
                    @endif
                </td>
                <td>
                    @if($s->hub_note)
                    <a href="javascript:void(0)"
                                class="note" rel="{!! $s->project_id !!}" note="{!! $s->hub_note !!}" level="{!! $s->hub_note_level !!}">
                    {{ $s->textNoteLevel() }} : {{ $s->hub_note }}
                    </a>
                    @else
                    <div class="process-btns">
                        <a href="javascript:void(0)"
                                class="btn-mini btn-danger note" rel="{!! $s->project_id !!}" note="{{ $s->hub_note }}" level="{!! $s->hub_note_level !!}">
                                <i class="fa fa-pencil fa-fw"></i>NOTE</a>
                    </div>
                    @endif

                </td>
                <td class="table--name">
                @if($s->hub_approve)
                    @if(Carbon::parse(env('SHOW_DATE'))->lt(Carbon::parse($s->date_added)))
                        @if(!$s->mail_send_time)
                            @if(!$s->hub_managers)
                            <a class="btn-mini btn-danger sendmail" href="javascript:void(0)" projectId="{{ $s->project_id }}"
                                projectTitle="{{ $s->textTitle() }}" userId="{{ $s->user_id }}" PM="">
                            @else
                            <a class="btn-mini btn-danger sendmail" href="javascript:void(0)" projectId="{{ $s->project_id }}"
                                projectTitle="{{ $s->textTitle() }}" userId="{{ $s->user_id }}" PM="{!! $s->hub_managers !!}">
                            @endif
                                <i class="fa fa-envelope fa-fw"></i> SEND
                            </a>
                        @else
                            @foreach ($s->mail_send_experts as $expert)
                                <p class="hub-manages">
                                    e:<a href="{!! $expert["link"] !!}" target="_blank">
                                    {!! $expert["id"] !!}
                                    </a>
                                </p>
                            @endforeach
                                <spna class="table--text-light">
                                    {{$s->mail_send_time}}<br>
                                    by {{ $s->mail_send_admin }}
                                </span>
                        @endif
                    @else
                        <spna class="table--text-center"><i class="fa fa-check"></i></span>
                    @endif
                @endif
                </td>
                <td>
                <td>
                    @if(!$s->hub_managers)
                        <p class="hub-manages">
                            <i class="fa fa-fw fa-exclamation-triangle"></i> No PM
                        </p>
                    @else
                        @foreach($s->getHubManagerNames() as $manager)
                        <p class="hub-manages">
                            <i class="fa fa-user fa-fw"></i> {!! $manager !!}
                        </p>
                        @endforeach

                        @foreach($s->getDeletedHubManagerNames() as $manager)
                        <p class="hub-manages">
                            <i class="fa fa-flash fa-fw"></i> {!! $manager !!}
                        </p>
                        @endforeach
                    @endif
                </td>
                </td>
                <td class="table-solid">
                    @if($s->isDeleted())
                        <a href="" class="btn btn-primary btn-disabled" disabled>This project was deleted</a>
                    @else
                        <div class="process-btns">
                            <a class="btn-mini" href="{!! $s->textFrontEditLink() !!}" target="_blank">
                                <i class="fa fa-pencil fa-fw"></i>EDIT
                            </a>

                            @if(!$s->hub_approve)
                            <a href="{!! action('HubController@approveSchedule', $s->project_id) !!}"
                               class="btn-mini btn-danger js-approve"><i class="fa fa-pencil fa-fw"></i>APPROVE</a>
                            @endif
                        </div>

                        <div class="version-btns">
                            <a href="{!! $preview['project'].$s->project_id  !!}"
                               target="_bland" class="btn-mini"><i class="fa fa-eye fa-fw"></i>LATEST</a>
                            <a href="{!! $preview['project'].$s->project_id  !!}"
                               target="_bland" class="btn-mini"><i class="fa fa-eye fa-fw"></i>LATEST</a>
                            <a href="{!! $preview['version'].$s->getOriginVersion()  !!}"
                               target="_bland" class="btn-mini"><i class="fa fa-eye fa-fw"></i>ORIGIN</a>

                            @if($s->hub_approve)
                            <a href="{!! $preview['version'].$s->getApproveVersion() !!}"
                               target="_bland" class="btn-mini"><i class="fa fa-eye fa-fw"></i>APPROVE</a>
                            @endif
                        </div>
                    @endif

                </td>
            </tr>
            @endforeach
        </table>

    </div>
</div>
<div id="note_dialog" class="ui-widget" title="Edit Note" style="display:none">
    <p>Note:</p>
    <select id="level">
        <option value="0">Not graded</option>
        <option value="1">Grade A</option>
        <option value="2">Grade B</option>
        <option value="3">Grade C</option>
        <option value="4">Grade D</option>
    </select>
    <textarea id="note" rows="4" cols="50"></textarea>
    <button id="edit_note">Edit Note</button>
    <input type="hidden" id="note_project_id" value="">
</div>
@include('hub.mail-dialog')
@stop



