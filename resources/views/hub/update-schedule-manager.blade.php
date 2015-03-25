@extends('layouts.master')
@include('layouts.macro')

@section('css')
<link rel="stylesheet" href="/css/questionnaire-detail.css">
@stop

@section('js')
@stop

@section('content')

<div class="page-header">
    <h1>
        #{!! $schedule->project_id !!}
        <a href="{!! $schedule->textFrontLink() !!}" target="_blank">{!! $schedule->textTitle() !!}</a>
    </h1>

    <div class="page-header--user">
        by <a href="{!! $user->textFrontLink()}}">{!! $user->textFullName() !!}</a>
        {!! HTML::image($user->getImagePath(), '', ['class' => 'user-avatar']) !!}
        @ {!! HTML::date($q->date_added) !!}
    </div>
</div>

<div id="questionnaire-detail">
    <div class="row">
        <div class="col-md-10 col-md-offset-1 clearfix">

            <div class="clearfix">
                <div class="data-group group-half">
                    <span class="label">Company</span>
                    <span class="content">{!! $q->company_name !!}</span>
                </div>
                <div class="data-group group-half">
                    <span class="label">Team Members</span>
                    <span class="content">{!! $q->members !!}</span>
                </div>
            </div>

            <div class="clearfix">
                <div class="data-group group-half">
                    <span class="label">Category</span>
                    <span class="content">{!! $q->textCategory() !!}</span>
                </div>

                <div class="data-group group-half">
                    <span class="label">Expect Ship Date</span>
                    <span class="content">{!! $q->ship_date !!}</span>
                </div>
            </div>

            <div class="clearfix">
                <div class="data-group group-half">
                    <span class="label">Location</span>
                    <span class="content">{!! $q->project_location !!}</span>
                </div>
                <div class="data-group group-half">
                    <span class="label">Target</span>
                    <span class="content">{!! $q->project_target !!}</span>
                </div>
            </div>

            <div class="clearfix">
                <div class="data-group group-half">
                    <span class="label">Expected 1st batch</span>
                    <span class="content">{!! $q->textFitstBatch() !!}</span>
                </div>
                <div class="data-group group-half">
                    <span class="label">Estimated budget</span>
                    <span class="content">{!! $q->textBudget() !!}</span>
                </div>
            </div>

            <div class="data-group">
                <span class="label">Project Stage</span>
                <span class="content">{!! $q->textProjectStage() !!}</span>
            </div>
        </div>

    </div>

    <div class="row">

        <div class="col-md-10 col-md-offset-1 clearfix">
            <div class="form-container">
                {!! Form::open(['action' => ['HubController@updateScheduleManager', $schedule->project_id],
                'method' => 'POST', 'class' => 'form-horizontal']) !!}

                <div class="form-group">
                    <label for="name" class="col-md-3">Managers</label>

                    <div class="col-md-8">
                        @foreach($adminers as $ad)
                        <div>
                            <input type='checkbox' name="manager[]" value='{!! $ad->id !!}' !!} />
                            <label for="">{!! $ad->name !!}</label>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="form-group">
                    <label for="" class="col-md-3"></label>

                    <div class="col-md-9">
                        <button class="btn-sassy btn-submit">UPDATE</button>
                    </div>
                </div>

                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>

@stop

