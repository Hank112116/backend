@extends('layouts.master')

@section('css')
    <link rel="stylesheet" href="{{ LinkGen::assets('css/landing-hello.css') }}">
@stop

@section('js')
    <script src="{{ LinkGen::assets('js/landing-hello.js') }}"></script>
@stop

@section('content')
    <div class="page-header">
        <h1>Hello</h1>
    </div>

    <div class="hello-url">
        <span class="hello-url__front" data-front-url="{{ env('FRONT_DOMAIN') }}/">{{ env('FRONT_DOMAIN') }}/</span>
        <input type="text" name="destination" class="hello-url__to" value="signup?status=2" />
    </div>

    <div class="hello-test">
        <button id="test" class="btn btn-success hello-test__button">Test Route</button>
        <button id="save" class="btn btn-primary hello-test__button">Change Route</button>
    </div>

    <div class="hello-latest-record">
        <table class="table table-striped">
            <tr>
                <th>#</th>
                <th>User</th>
                <th>IP</th>
                <th>Browser</th>
                <th>Redirect</th>
                <th>Date</th>
            </tr>

            @foreach($records as $record)
            <tr>
                <td>{{ $record->log_id }}</td>
                <td>@include('layouts.user', ['user' => $record->user])</td>
                <td>{{ $record->user_ip }}</td>
                <td>{{ $record->browser->getName() }} {{ $record->browser->getVersion() }}</td>
                <td>{{ $record->destination }}</td>
                <td>{{ $record->update_time }}</td>
            </tr>
            @endforeach
        </table>
    </div>

@stop

