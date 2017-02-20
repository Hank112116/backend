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
    <script src="{{ LinkGen::assets('js/user-list.js') }}"></script>
@stop

@section('content')
<div class="page-header">
    <h1>{{ $title ?: 'Members' }}
        @if(count($to_expert_ids) > 0)
        <span class='header-notice fa fa-bell icon-vibrate'></span>
        @endif
    </h1>

    @if(!$is_restricted)
        @include('layouts.get-csv')
    @endif
</div>

@include ('user.list-search')

<div class="row">
    <div class="col-md-12">
        <table class="table table-striped float-thead">
            <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                @if(auth()->user()->isAdmin())
                <th>Internal PM</th>
                @endif
                <th>Role</th>
                @if(auth()->user()->isManagerHead() || auth()->user()->isAdmin())
                    <th class="table--user-mail">Email</th>
                @endif
                <th>Country<br/>City</th>
                <th class="table--width-limit">
                    Company<br/>
                    <span class="table--text-light">Position</span>
                </th>
                <th>
                    Registed On
                    @if(!$is_restricted)
                        <br/><span class="table--text-light">Signup Ip</span>
                    @endif
                </th>
                <th>Email<br/>Verify</th>
                <th>Active</th>
                <th>Internal description</th>
                <th>Action</th>
                <th>Expertise tags<br/><span class="table--text-light">Internal tags</span></th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach($users as $user)
                <tr id="row-{{ $user->user_id }}">
                    @include('user.row', ['user' => $user, 'is_restricted' => $is_restricted])
                </tr>
            @endforeach
            </tbody>
            <input type="hidden" id="route-path" value="{{ Route::current()->uri() }}">
        </table>
    </div>
</div>
@include('user.dialog.apply-expert-message-dialog')
@include('user.dialog.add-tags-dialog')
@include('user.dialog.description-dialog')
@include('report.dialog.user-report-action')
<div class="text-center">
    {!! $users->appends(request()->all())->render() !!}
</div>

@stop

