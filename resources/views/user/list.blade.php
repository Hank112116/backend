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
                    <th class="table--user-mail">EMail</th>
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
                <th>EMail<br/>Verify</th>
                <th>Active</th>
                <th></th>
            </tr>

            @foreach($users as $user)
                <tr>
                    <td>{{ $user->user_id }}</td>
                    <td>
                        <a href="{!! $user->textFrontLink() !!}" target="_blank">
                            {{ $user->textFullName() }}</a>
                    </td>
                    @if(Auth::user()->isAdmin())
                    <td>{!! $user->textHWTrekPM() !!}<br>
                        @if(!$user->isHWTrekPM())
                             <span class="user-sub-category">
                                <input type="checkbox"  class="change_pm" rel="{!! $user->user_id !!}"> To PM
                            </span>
                        @else
                            <span class="user-sub-category">
                                <input type="checkbox"  class="change_user" rel="{!! $user->user_id !!}"> To User
                            </span>
                        @endif
                    </td>
                    @endif

                    @if(Auth::user()->isAdmin() && $user->isHWTrekPM())
                        <td>{!! $user->textHWTrekPM() !!}({!! $user->textType() !!})</td>
                    @else
                        <td>{!! ($user->isToBeExpert() && $user->isCreator())?'<font color="red">To Be Expert</font>':$user->textType()  !!}</td>
                    @endif

                    @if(Auth::user()->isManagerHead() || Auth::user()->isAdmin())
                    <td class="table--user-mail">
                        {{ $user->email }}
                        @if('facebook' === $user->social)
                            <i class="fa fa-facebook-square"></i>
                        @elseif('linkedin' === $user->social)
                            <i class="fa fa-linkedin-square"></i>
                        @endif
                    </td>
                    @endif
                    <td>{{ $user->country }}<br/>{{ $user->city }}</td>

                    <td class="table--width-limit">
                        {{ $user->company }}<br/>
                        <span class="table--text-light">{{ $user->business_id  }}</span>
                    </td>

                    <td>
                        <span data-time="{!! $user->date_added !!}">{!! HTML::date($user->date_added) !!}</span>
                        @if(!$is_restricted)
                            <br/><span class="table--text-light">{!! $user->signup_ip !!}</span>
                        @endif
                    </td>

                    <td>{!! $user->textEmailVerify() !!}</td>
                    <td>{!! $user->textActive() !!}</td>

                    <td>
                        {!! link_to_action(
                                'UserController@showDetail', 'DETAIL',
                                $user->user_id, ['class' => 'btn-mini']) !!}

                        @if(!$is_restricted and !$user->isExpert() and $user->isToBeExpert())
                            {!! link_to_action(
                                    'UserController@showUpdate', 'EDIT & To-Expert',
                                    $user->user_id, ['class' => 'btn-mini btn-danger']) !!}
                        @else
                            {!! link_to_action(
                                    'UserController@showUpdate', 'EDIT',
                                    $user->user_id, ['class' => 'btn-mini']) !!}
                        @endif
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
</div>

@include('layouts.paginate', ['collection' => $users, 'per_page' => isset($per_page)? $per_page : ''])

@stop

