@extends('layouts.master')

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
                <th></th>
            </tr>

            @foreach($schedules as $s)
            <tr>
                <td>{!! $s->project_id !!}</td>
                <td class="table--name">
                    <a href="{!! $s->textFrontLink() !!}" target="_blank">{!! $s->project_title !!}</a>
                </td>
                <td>
                    @if($s->user)
                    <a href="{!! $s->user->textFrontLink() !!}" target="_blank">{!! $s->user->textFullName() !!}</a>
                    @endif
                </td>
                <td>
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

@stop



