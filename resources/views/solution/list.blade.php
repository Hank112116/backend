@extends('layouts.master')
@include('layouts.macro')

@section('css')
    @cssLoader('solution-list')
@stop

@section('js')
    @jsLoader('list')
@stop

@section('content')

<div class="page-header">
    <h1>
        {!! $title !!}

        @if($has_wait_approve_solutions)
        <span class='header-notice fa fa-bell icon-vibrate'></span>
        @endif
    </h1>

    @if(!$is_restricted)
        @include('layouts.get-csv')
    @endif
</div>

<div class="search-bar">
    @include ('solution.list-search')
</div>

<div class="row">

    <div class="col-md-12">

        <table class="table table-striped">
            <tr>
                <th>#</th>
                <th class='table--name'>Title</th>
                <th>Category</th>
                <th class='table--name'>User</th>
                <th>Country<br/>City</th>
                <th>Status</th>
                <th>Approve<br/>Date</th>
                <th>Last Update</th>
                <th>Manager<br/>Approved</th>
                <th></th>
            </tr>

            @foreach($solutions as $solution)
            <tr>
                <td>{!! $solution->solution_id !!}</td>

                <td class="table--name">
                    <a href="{!! $solution->textFrontLink() !!}" target="_blank">
                        {{ $solution->textTitle() }}
                    </a>
                </td>

                <td>
                    {!! $solution->textMainCategory() !!} <br/>

                    <span class="solution-sub-category">
                        {!! $solution->textSubCategory() !!}
                    </span>
                </td>

                <td>
                    <a href="{!! $solution->user->textFrontLink() !!}" target="_blank">
                        {{ $solution->user->textFullName() }}
                    </a>
                </td>

                <td>{{ $solution->user->country }}<br/>{{ $solution->user->city }}</td>

                <td>
                    {!! $solution->textStatus() !!}
                    @if($solution->is_deleted)
                        <br/>Deleted ({{$solution->deleted_date}})
                    @endif
                </td>

                <td>{!! HTML::date($solution->approve_time) !!}</td>
                <td>{!! HTML::time($solution->update_time) !!}</td>

                <td>
                    @if($solution->is_manager_approved)
                        <i class="fa fa-check"></i>
                    @endif
                </td>

                <td>
                    {!! link_to_action('SolutionController@showDetail', 'DETAIL',
                    $solution->solution_id, ['class' => 'btn-mini']) !!}

                    @if($solution->is_wait_approve_draft)

                        {!! link_to_action( 'SolutionController@showUpdate', 'Edit & Approve',
                        $solution->solution_id, ['class' => 'btn-mini btn-danger']) !!}

                    @elseif($solution->is_wait_approve_ongoing)

                        <a href="{!! action('SolutionController@showUpdate', $solution->solution_id) !!}" class="btn-mini btn-danger">
                            Edit & Approve <i class="fa fa-copy"></i>
                        </a>

                    @else

                        {!! link_to_action( 'SolutionController@showUpdate', 'EDIT',
                        $solution->solution_id, ['class' => 'btn-mini']) !!}

                    @endif
                </td>
            </tr>
            @endforeach
        </table>

    </div>
</div>

@include('layouts.paginate', ['collection' => $solutions, 'per_page' => isset($per_page)? $per_page : ''])


@stop



