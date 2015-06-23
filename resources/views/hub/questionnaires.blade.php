@extends('layouts.master')
@include('layouts.macro')
@section('jqui')
    @include('layouts.jqui')
@stop
@section('css')
    @cssLoader('hub-questionnaires')
@stop

@section('js')
    @jsLoader('hub-questionnaires')
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
<div id="dialog" class="ui-widget" title="Send mail" style="display:none">
    <table class="table table-striped">
        <tr>
            <td>Expert</td>
            <td>
                <input type="text" id="expert1" placeholder="Expert Id">            
            </td>
            <td class="table--text-left">
                <span id="expert1Info"></span>
            </td>
        </tr>
        <tr>
            <td>Expert</td>
            <td>
                <input type="text" id="expert2" placeholder="Expert Id">
            </td>
            <td class="table--text-left">
                <span id="expert2Info"></span>
            </td>
        </tr>

    </table>
    <input type="hidden" id="projectId">
    <input type="hidden" id="projectTitle">
    <input type="hidden" id="userId">
    <input type="hidden" id="PM">
    <button id="sendMail">SendMail</button>
</div>
@include('layouts.paginate', ['collection' => $questionnaires, 'per_page' => isset($per_page)? $per_page : ''])


@stop



