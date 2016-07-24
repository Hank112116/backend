@extends('layouts.master')
@include('layouts.macro')

@section('css')
    <link rel="stylesheet" href="{{ LinkGen::assets('css/product-list.css') }}">
@stop

@section('jqui')
    @include('layouts.jqui')
@stop

@section('js')
    <script src="{{ LinkGen::assets('js/list.js') }}"></script>
    <script src="{{ LinkGen::assets('js/project-list.js') }}"></script>
@stop

@section('content')

    <div class="page-header">
        <h1>{!! $title !!}</h1>
    </div>

    <div class="search-bar">
        @include('report.project-search')
    </div>
    <div class="row text-center">
        <h4>
            @if (Input::get('range'))
                Showing Match Stats from {{ $input['dstart'] }} to {{ $input['dend'] }}
            @else
                Showing Search Results
            @endif
        </h4>
    </div>
    <div class="row text-center">
        <h4>
            {{ $projects->total() }} Projects in This Period: {{ $projects->public_count }} Expert mode | {{ $projects->private_count }} Private mode | {{ $projects->draft_count }} Draft | {{ $projects->delete_count }} Deleted <br/><br/>
            @if ($match_statistics)
                <?php $i = 1; ?>
                {{ $match_statistics['recommend_total'] }} Internal Referrals:
                @foreach($match_statistics['item'] as $name => $statistic)
                    {{ $name }}: {{ $statistic['recommend_count'] }} |
                    @if ($i % 7 == 0)
                        <br/>
                    @endif
                    <?php $i++; ?>
                @endforeach
            @endif
            <br/><br/>
            {{ $projects->user_referrals }} User Referrals
        </h4>
        @if ($match_statistics)
            <button class="btn btn-primary match-statistics-btn" type="button">Match Statistics</button>
            <input type="hidden" name="match-statistics" id="match-statistics" value="{{ json_encode($match_statistics['item']) }}">
        @endif
    </div><br/>
    <div class="row">
        <div class="col-md-12">
            <table class="table table-striped float-thead">
                <thead>
                <tr>
                    <th>#</th>
                    <th class="table--name">Project Title<br/><span class="table--text-light">Category</span></th>
                    <th>Owner<br/><span class="table--text-light">Country</span></th>
                    <th>Status<br/><span class="table--text-light">Status Change</span></th>
                    <th>Company</th>
                    <th>Assigned PM</th>
                    <th>Proposed</th>
                    <th>Referrals</th>
                    <th>Created On<br/><span class="table--text-light">Last Update</span></th>
                    <th>Action</th>
                    <th></th>
                </tr>
                </thead>
                <?php $user_referral_total = 0 ?>
                <tbody>
                @foreach($projects as $project)
                    <tr id="row-{{ $project->project_id }}">
                        @include('report.project-row', ['project' => $project])
                    </tr>
                @endforeach
                </tbody>
            </table>
            @include('project.dialog.propose-solution-dialog')
            @include('project.dialog.recommend-expert-dialog')
            @include('report.dialog.project-report-action')
            @include('report.dialog.project-match-statistics')
        </div>
    </div>
    <div class="text-center">
    {!! $projects->appends(Input::all())->render() !!}
    </div>
    @if ($input['time_type'] == 'match')
        <input type="hidden" id="statistic-start-date" value="{{ $input['dstart'] }}">
        <input type="hidden" id="statistic-end-date" value="{{ $input['dend'] }}">
        <input type="hidden" id="time_type" value="{{ $input['time_type'] }}">
    @else
        <input type="hidden" id="statistic-start-date" value="">
        <input type="hidden" id="statistic-end-date" value="">
        <input type="hidden" id="time_type" value="">
    @endif
    <input type="hidden" id="route-path" value="{{ Route::getCurrentRoute()->getPath() }}">
@stop

