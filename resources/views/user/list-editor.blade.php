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
                    <th></th>
                </tr>

                @foreach($users as $user)
                    <tr>
                        <td>{{ $user->user_id }}</td>
                        <td>
                            @if ($user->isSuspended())
                                {{ $user->textFullName() }} ( {{ $user->textStatus() }} )
                            @else
                                <a href="{!! $user->textFrontLink() !!}" target="_blank">{{ $user->textFullName() }}</a>
                            @endif
                        </td>
                        <td>{!! ($user->isToBeExpert() && $user->isCreator())?'<font color="red">To Be Expert</font>':$user->textType()  !!}</td>
                        <td>{{ $user->country }}<br/><span class="table--text-light">{{ $user->city }}</span></td>
                        <td class="table--width-limit">
                            {{ $user->company }}<br/>
                            <span class="table--text-light">{{ $user->business_id  }}</span>
                        </td>
                        <td>
                            <span data-time="{!! $user->date_added !!}">{{ $user->textRegistedOn() }}</span>
                        </td>
                        <td>{!! $user->textEmailVerify() !!}</td>
                        <td>{!! $user->textActive() !!}</td>
                        <td>
                            {!! link_to_action(
                                    'UserController@showDetail', 'DETAIL',
                                    $user->user_id, ['class' => 'btn-mini']) !!}
                            {!! link_to_action(
                                    'UserController@showUpdate', 'EDIT',
                                    $user->user_id, ['class' => 'btn-mini']) !!}
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>

    @include('layouts.paginate', ['collection' => $users, 'per_page' => isset($per_page)? $per_page : ''])

@stop

