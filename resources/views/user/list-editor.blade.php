@extends('layouts.master')
@include('layouts.macro')

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
    </div>

    @include ('user.list-search')

    <div class="row">
        <div class="col-md-12">
            <table class="table table-striped">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Role</th>
                    <th>Country<br/>
                        <span class="table--text-light">City</span>
                    </th>
                    <th class="table--width-limit">
                        Company<br/>
                        <span class="table--text-light">Position</span>
                    </th>
                    <th>Registed On</th>
                    <th>Email<br/>Verify</th>
                    <th>Active</th>
                    <th>Expertise tags<br/><span class="table--text-light">Internal tags</span></th>
                    <th>Internal description</th>
                    <th>Action</th>
                    <th></th>
                </tr>

                @foreach($users as $user)
                    <tr>

                    </tr>
                @endforeach
            </table>
        </div>
    </div>

    @include('layouts.paginate', ['collection' => $users, 'per_page' => isset($per_page)? $per_page : ''])

@stop

