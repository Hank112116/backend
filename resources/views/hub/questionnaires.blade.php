@extends('layouts.master')
@include('layouts.macro')
@section('jqui')
    @include('layouts.jqui')
@stop
@section('css')
    <link rel="stylesheet" href="{{ LinkGen::assets('css/hub-questionnaires.css') }}">
@stop

@section('js')
    <script src="{{ LinkGen::assets('js/hub-questionnaires.js') }}"></script>
@stop

@section('content')

<div class="page-header">
    <h1>Questionnaire</h1>

    @if($is_restricted)
    <p class="page-header--notice">
        <i class="fa fa-smile-o fa-fw"></i>
        Login HWTrek Before
        <a class="btn-mini" href="#"><i class="fa fa-pencil fa-fw"></i>EDIT SCHEDULE</a>
    </p>
    @endif

</div>

<div class="row">
    <div class="col-md-12">

        <table class="table table-striped">
            <tr>
                <th>#</th>
                <th>P.ID</th>
                <th class="table--name">Project</th>
                <th>Category</th>
                <th class="table--name">User<br/><span class="table--text-light">Company</span></th>
                <th class="table--text-right">Estimate Quantity</th>
                <th>Shipout Date</th>
                <th>Submit Date</th>
                <th>Status</th>
                <th>Approved</th>
                <th>PM</th>
                <th>Note</th>
                <th></th>
            </tr>

            @foreach($questionnaires as $q)
                @if($q->schedule)
                    @include('hub.q-row')
                @else
                   @include('hub.q-deleted-row')
                @endif
            @endforeach
        </table>

    </div>
</div>
@include('hub.mail-dialog')
@include('layouts.paginate', ['collection' => $questionnaires, 'per_page' => isset($per_page)? $per_page : ''])


@stop



