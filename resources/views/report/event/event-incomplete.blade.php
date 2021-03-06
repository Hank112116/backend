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
    <script src="{{ LinkGen::assets('js/event-report-list.js') }}"></script>
@stop

@section('content')
    <div class="page-header">
        <h1>{{ $title or 'Registration Report' }}</h1>
    </div>
    @include ('report.event-search')
    <div class="row">
        <div class="col-md-12">
            <table class="table table-striped">
                <tr>
                    <th>#</th>
                    <th>ID</th>
                    <th>Email</th>
                    <th class="table--width-limit">
                        Enter Time (PST)
                    </th>
                    <th class="table--width-limit">
                        Complete On (PST)
                    </th>
                    <th>Note</th>
                </tr>

                @foreach($event_users as $no => $event_user)
                    <tr>
                        <td>{!! $begin_number -- !!}</td>

                        <td>{!! $event_user->id !!}</td>

                        <td class="table--user-mail">
                            {{ $event_user->email }}
                        </td>

                        <td class="table--width-limit">
                            {{ $event_user->textEnterTime() }}<br/>
                        </td>

                        <td class="table--width-limit">
                            {{ $event_user->getCompleteTime() }}<br/>
                        </td>

                        <td>
                            @if(!empty($event_user->note))
                                <a href="javascript:void(0)"
                                   class="note" rel="{!! $event_user->id !!}" note="{{ $event_user->note }}">
                                    <i class="fa fa-pencil"></i>
                                    {{ mb_strimwidth($event_user->note, 0, 130, mb_substr($event_user->note, 0, 130) . '...') }}
                                </a>
                            @else
                                <div class="process-btns">
                                    <a href="javascript:void(0)"
                                       class="btn-mini btn-danger note" rel="{!! $event_user->id !!}" note="{{ $event_user->note }}" >
                                        <i class="fa fa-pencil fa-fw"></i>NOTE</a>
                                </div>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
    <div class="text-center">
        {!! $event_users->appends(request()->all())->render() !!}
    </div>
    @include ('report.dialog.event-note-dialog')
@stop

