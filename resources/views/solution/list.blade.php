@extends('layouts.master')
@include('layouts.macro')

@section('css')
    <link rel="stylesheet" href="/css/solution-list.css">
@stop

@section('js')
    <script src='/js/list.js'></script>
    <script src='/js/solution-list.js'></script>
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
                <th>Type</th>
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
                    {!! $solution->textType() !!} <br/>
                    @if(Auth::user()->isBackendPM() || Auth::user()->isManagerHead() || Auth::user()->isAdmin())
                        @if($solution->isSolution())                     
                             <span class="solution-sub-category">
                                <input type="checkbox"  class="approve_program" rel="{!! $solution->solution_id !!}"> To Program
                            </span>
                        @elseif($solution->isProgram())
                            <span class="solution-sub-category">
                                <input type="checkbox"  class="approve_solution" rel="{!! $solution->solution_id !!}"> To Solution
                            </span>
                        @elseif($solution->isPendingSolution())
                            <span class="solution-sub-category">
                                <input type="checkbox"  class="cancel_solution" rel="{!! $solution->solution_id !!}"> Cancel
                            </span>
                        @elseif($solution->isPendingProgram())
                            <span class="solution-sub-category">
                                <input type="checkbox"  class="cancel_program" rel="{!! $solution->solution_id !!}"> Cancel
                            </span>
                        @endif
                    @endif
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

                <td>{{ $solution->textApproveDate() }}</td>

                <td>{{ $solution->textLastUpdateDate() }}</td>

                <td>
                    @if($solution->is_manager_approved)
                        <i class="fa fa-check"></i>
                    @endif
                </td>

                <td>
                    {!! link_to_action('SolutionController@showDetail', 'DETAIL',
                    $solution->solution_id, ['class' => 'btn-mini']) !!}

                    @if($solution->is_wait_approve_draft && !$is_restricted)
                        @if(!Auth::user()->isFrontendPM())
                            {!! link_to_action( 'SolutionController@showUpdate', 'Edit & Approve',
                            $solution->solution_id, ['class' => 'btn-mini btn-danger']) !!}
                        @endif
                    @elseif($solution->is_wait_approve_ongoing && !$is_restricted)
                        @if(!Auth::user()->isFrontendPM())
                            <a href="{!! action('SolutionController@showUpdate', $solution->solution_id) !!}" class="btn-mini btn-danger">
                                Edit & Approve <i class="fa fa-copy"></i>
                            </a>
                        @endif
                    @else

                        {!! link_to_action( 'SolutionController@showUpdate', 'EDIT',
                        $solution->solution_id, ['class' => 'btn-mini']) !!}

                    @endif

                    @if(Auth::user()->isAdmin() || Auth::user()->isManagerHead())
                        @if($solution->isPendingProgram())
                            <a href="javascript:void(0)" title="Upgrade Solution to Program" class="btn-mini btn-danger approve_pending_program" rel="{!! $solution->solution_id !!}">
                                <i class="fa fa-long-arrow-up"></i> Up
                            </a>
                        @endif
                        @if($solution->isPendingSolution())
                            <a href="javascript:void(0)" title="Change Program to Solution" class="btn-mini btn-danger approve_pending_solution" rel="{!! $solution->solution_id !!}">
                                <i class="fa fa-long-arrow-down"></i> Down
                            </a>
                        @endif
                    @endif
                </td>
            </tr>
            @endforeach
        </table>

    </div>
</div>

@include('layouts.paginate', ['collection' => $solutions, 'per_page' => isset($per_page)? $per_page : ''])


@stop



