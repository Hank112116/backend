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
    <script src="{{ LinkGen::assets('js/member-matching.js') }}"></script>
@stop

@section('content')
    <div class="page-header">
        <h1>Member Matching Report</h1>
    </div>
    @include ('report.member-match.search')
    <div class="row text-center">
        <h4>
            Showing Match Stats from {{ $input['dstart'] }} to {{ $input['dend'] }}
        </h4>
    </div>
    <div class="row col-md-offset-2">
        <div class="text-center col-md-10">
            @foreach($members->pm_statistic as $item)
                @if ($loop->iteration % 7 != 0)
                {{ $item->name() }}: {{ $item->getCountReferral() }}
                    |
                @else
                    <br/>
                @endif
            @endforeach
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <table class="table table-striped float-thead">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Active<br/>
                        <span class="table--text-light">Email Verify</span>
                    </th>
                    <th>Role</th>
                    <th>Company</th>
                    <th>Recommend</th>
                    <th>Referrals</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach($members as $member)
                    <tr>
                        <td>{{ $member->id() }}</td>
                        <td>
                            @if ($member->isSuspended())
                                {{ $member->fullName() }} ( {{ $member->textStatus() }} )
                            @else
                                <a href="{!! $member->link() !!}" target="_blank">{{ $member->fullName() }}</a>
                            @endif
                        </td>
                        <td>
                            {{ $member->textActive() }}<br/>
                            <span class="table--text-light">{{ $member->textEmailVerify() }}</span>
                        </td>
                        <td>{{ $member->role() }}</td>
                        <td>
                            <a href="{{ $member->companyLink() }}" target="_blank">
                                {{ $member->company() }}
                            </a>
                        </td>
                        <td>
                            <a href="javascript:void(0)" class="matching-data" rel="{{ $member->id() }}">
                                PM: {{ $member->getCountPMRecommend() }}<br/>
                                User: {{ $member->getCountUserRecommend() }}
                            </a>
                        </td>
                        <td>
                            <a href="javascript:void(0)" class="matching-data" rel="{{ $member->id() }}">
                                PM: {{ $member->getCountPMReferrals() }} <br/>
                                User: {{ $member->getCountUserReferrals() }}
                            </a>
                        </td>
                        <td>
                            @if($member->internalAction())
                                <a href="javascript:void(0)" class="user-report-action" rel="{!! $member->id() !!}" action="{{ $member->internalAction() }}">
                                {{ str_limit($member->internalAction(), 130) }}
                                </a>
                            @else
                                <a href="javascript:void(0)" class="btn-mini user-report-action" rel="{!! $member->id() !!}" action="">Add</a>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="text-center">
        {!! $members->appends(request()->all())->render() !!}
        <input type="hidden" id="route-path" value="{{ Route::getCurrentRoute()->getPath() }}">
    </div>
    @include('report.dialog.user-report-action')
    @include('report.member-match.dialog.dialog')
@stop
