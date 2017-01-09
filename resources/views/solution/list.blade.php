@extends('layouts.master')
@include('layouts.macro')

@section('css')
    <link rel="stylesheet" href="{{ LinkGen::assets('css/solution-list.css') }}">
@stop

@section('js')
    <script src="{{ LinkGen::assets('js/list.js') }}"></script>
    <script src="{{ LinkGen::assets('js/solution-list.js') }}"></script>
@stop

@section('content')

<div class="page-header">
    <h1>
        Solutions

        @if($has_wait_approve_solutions)
        <span class='header-notice fa fa-bell icon-vibrate'></span>
        @endif
    </h1>

    <div id="output_all" class='header-output'>
        @if(auth()->user()->isAdmin())
            <a class="btn-mini header-output-link" href="{!! LinkGen::csv(true, $total_count) !!}">
                CSV
            </a>
        @endif
    </div>
</div>

<div class="search-bar">
    @include ('solution.list-search')
</div>

<div class="row">

    <div class="col-md-12">

        <table class="table table-striped float-thead">
            <thead>
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
            </thead>

            <tbody>
            @foreach($solutions as $solution)
            <tr>
                <td>{!! $solution->getId() !!}</td>

                <td class="table--name">
                    <a href="{!! $solution->getUrl() !!}" target="_blank">
                        {{ $solution->getTitle() }}
                    </a>
                </td>
                <td>
                    {!! $solution->getTextType() !!} <br/>
                    @if(auth()->user()->isBackendPM() || auth()->user()->isManagerHead() || auth()->user()->isAdmin())
                        @if($solution->isSolution())
                             <span class="solution-sub-category">
                                <input type="checkbox"  class="approve_program" rel="{!! $solution->getId() !!}"> To Program
                            </span>
                        @elseif($solution->isProgram())
                            <span class="solution-sub-category">
                                <input type="checkbox"  class="approve_solution" rel="{!! $solution->getId() !!}"> To Solution
                            </span>
                        @elseif($solution->isPendingSolution())
                            <span class="solution-sub-category">
                                <input type="checkbox"  class="cancel_solution" rel="{!! $solution->getId() !!}"> Cancel
                            </span>
                        @elseif($solution->isPendingProgram())
                            <span class="solution-sub-category">
                                <input type="checkbox"  class="cancel_program" rel="{!! $solution->getId() !!}"> Cancel
                            </span>
                        @endif
                    @endif
                </td>
                <td>
                    {!! $solution->getTextCategory() !!} <br/>

                    <span class="solution-sub-category">
                        {!! $solution->getTextSubCategory() !!}
                    </span>
                </td>

                <td>
                    <a href="{!! $solution->getOwnerUrl() !!}" target="_blank">
                        {{ $solution->getOwnerFullName() }}
                    </a>
                </td>

                <td>{{ $solution->getOwnerLocation() }}</td>

                <td>
                    {!! $solution->getTextStatus() !!}
                    @if($solution->isDeleted())
                        <br/><i class="fa fa-trash" title="deleted"></i> <span class="table--text-light">{{ $solution->getTextDeleteDate() }}</span>
                    @endif
                </td>

                <td>{{ $solution->getTextApprovedDate() }}</td>

                <td>{{ $solution->getTextLastUpdateDate() }}</td>

                <td>
                    @if($solution->isManagerApproved())
                        <i class="fa fa-check"></i>
                    @endif
                </td>

                <td>
                    {!! link_to_action('SolutionController@showDetail', 'DETAIL',
                    $solution->getId(), ['class' => 'btn-mini']) !!}

                    @if($solution->isWaitPublish() && !$is_restricted)
                        @if(!auth()->user()->isFrontendPM())
                            {!! link_to_action( 'SolutionController@showUpdate', 'Edit & Approve',
                            $solution->getId(), ['class' => 'btn-mini btn-danger']) !!}
                        @endif
                    @else
                        {!! link_to_action( 'SolutionController@showUpdate', 'EDIT',
                        $solution->getId(), ['class' => 'btn-mini']) !!}
                    @endif

                    @if(auth()->user()->isAdmin() || auth()->user()->isManagerHead())
                        @if($solution->isPendingProgram())
                            <a href="javascript:void(0)" title="Upgrade Solution to Program" class="btn-mini btn-danger approve_pending_program" rel="{!! $solution->getId() !!}">
                                <i class="fa fa-long-arrow-up"></i> Up
                            </a>
                        @endif
                        @if($solution->isPendingSolution())
                            <a href="javascript:void(0)" title="Change Program to Solution" class="btn-mini btn-danger approve_pending_solution" rel="{!! $solution->getId() !!}">
                                <i class="fa fa-long-arrow-down"></i> Down
                            </a>
                        @endif
                    @endif
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>

    </div>
</div>

<div class="text-center">
    {!! $solutions->appends(request()->all())->render() !!}
</div>


@stop



