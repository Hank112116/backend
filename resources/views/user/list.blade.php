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
    <script src='/js/user-list.js'></script>
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
        <table class="table table-striped">
            <tr>
                <th>#</th>
                <th>Name</th>
                @if(Auth::user()->isAdmin())
                <th>Internal PM</th>
                @endif
                <th>Role</th>
                @if(Auth::user()->isManagerHead() || Auth::user()->isAdmin())
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
                <th>Expertise tags<br/><span class="table--text-light">Internal tags</span></th>
                <th>Internal description</th>
                <th>Action</th>
                <th></th>
            </tr>

            @foreach($users as $user)
                <tr id="row-{{ $user->user_id }}">
                    @include('user.row', ['user' => $user, 'is_restricted' => $is_restricted])
                </tr>
            @endforeach
            <input type="hidden" id="route-path" value="{{ Route::getCurrentRoute()->getPath() }}">
        </table>
    </div>
</div>
@include('user.dialog.apply-expert-message-dialog')
@include('user.dialog.add-tags-dialog')
@include('user.dialog.description-dialog')
@include('report.dialog.user-report-action')
@include('layouts.paginate', ['collection' => $users, 'per_page' => isset($per_page)? $per_page : ''])

@stop

