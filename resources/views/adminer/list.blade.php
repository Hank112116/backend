@extends('layouts.master')

@section('css')
    <link rel="stylesheet" href="/css/adminer-list.css">
@stop

@section('js')
    <script src='/js/adminer-list.js'></script>
@stop

@section('content')
<div class="page-header">
    <h1>Team Members</h1>

    <div class='header-output'>
        {!! link_to_action('AdminerController@showCreate',
        'New Member', '',['class' => 'btn-mini header-output-link']) !!}
        {!! link_to_action('AdminerController@showRoleCreate',
        'New Role', '',['class' => 'btn-mini header-output-link']) !!}
    </div>

    @include('layouts.get-csv')

</div>

<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <table class="table table-striped">
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>EMail</th>
                <th>Role</th>
                <th>HWTrek</th>
                <th></th>
            </tr>

            @foreach($adminers as $adminer)
            <tr>
                <td>{!! $adminer->id !!}</td>
                <td>{!! $adminer->name !!}</td>
                <td>{!! $adminer->email !!}</td>
                <td>{!! $adminer->role->name !!}</td>
                <td>
                    @if($adminer->user)
                        <a href="{!! $adminer->user->textFrontLink() !!}" target="_blank">
                            {!! $adminer->user->textFullName() !!}
                        </a>
                    @endif
                </td>
                <td>
                    {!! link_to_action(
                    'AdminerController@showUpdate', 'EDIT',
                    $adminer->id, ['class' => 'btn-mini']) !!}

                    {!! link_to_action(
                    'AdminerController@delete', 'DELETE',
                    $adminer->id, ['class' => 'btn-mini js-delete']) !!}
                </td>
            </tr>
            @endforeach

            @foreach($deleted_adminers as $adminer)
                <tr>
                    <td>{!! $adminer->id !!}</td>
                    <td>{!! $adminer->name !!}</td>
                    <td>{!! $adminer->email !!}</td>
                    <td>{!! $adminer->role->name !!}</td>
                    <td></td>
                    <td>Deleted</td>
                </tr>
            @endforeach
        </table>

    </div>
</div>

<div class="page-header">
    <h1>Roles</h1>
    {{-- not open to adminer
    <div class='header-output'>
        {!! link_to_action('AdminerController@showRoleCreate',
        'New Role', '',['class' => 'btn-mini header-output-link']) !!}
    </div>
    --}}
</div>

<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <table class="table table-striped">
            <tr>
                <th>Role</th>
                <th class="cert-column"></th>
                <th></th>
            </tr>

            @foreach($roles as $role)
            <tr>
                <td>{!! $role->name !!}</td>
                <td>
                    @foreach($role->getCertsArr() as $code)
                        <span class="cert-tag"><i class="fa fa-lock fa-fw"></i>{!! $role->getCertName($code) !!}</span>
                    @endforeach
                </td>
                <td>
                    {!! link_to_action( 'AdminerController@showRoleUpdate', 'EDIT',
                        $role->id, ['class' => 'btn-mini']) !!}

                    {{-- not open to adminer
                        {!! link_to_action( 'AdminerController@roleDelete', 'DELETE',
                        $role->id, ['class' => 'btn-mini']) !!}
                    --}}
                </td>
            </tr>
            @endforeach
        </table>
    </div>
</div>

@stop

