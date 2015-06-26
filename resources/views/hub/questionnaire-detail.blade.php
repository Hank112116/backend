@extends('layouts.master')
@include('layouts.macro')

@section('css')
    <link rel="stylesheet" href="/css/questionnaire-detail.css">
@stop

@section('content')

<div class="page-header">
    <h1>
        #{!! $schedule->project_id !!}

        @if($q->schedule)
            <a href="{!! $schedule->textFrontLink() !!}" target="_blank">
                {{ $schedule->textTitle() }}
            </a>
        @else
            This Project had been Deleted
        @endif
    </h1>

    <div class="page-header--user">
        by <a href="{!! $user->textFrontLink() !!}">{{ $user->textFullName() }}</a>
        {!! HTML::image($user->getImagePath(), '', ['class' => 'user-avatar']) !!}
        @ {!! HTML::date($q->date_added) !!}
    </div>
</div>

<div id="questionnaire-detail">
    <div class="row">
        <div class="col-md-10 col-md-offset-1 clearfix">

            @if($q->schedule and Auth::user()->role->hasCert('schedule_manager'))

            {!! Form::open(['action' => ['HubController@updateScheduleManager', $schedule->project_id],
            'method' => 'POST']) !!}
            <div class="panel panel-default">
                <div class="panel-heading">
                   Schedule Manager
                </div>
                <div class="panel-body">

                    <div>
                        {{--<h4>View Questionnaire & Manage Schedule</h4>--}}
                        @foreach($adminers as $ad)
                        @if(!$ad->role->isAdmin() and !$ad->role->isManagerHead())
                        <div class="adminer-tag">
                            <input id="adminer_{{$ad->id}}" type='checkbox' name="managers[]" value='{!! $ad->id !!}' !!}
                            {!! $schedule->inHubManagers($ad->id)? 'checked' : '' !!} />
                            <label for="adminer_{{$ad->id}}">{!! $ad->name !!}</label>
                        </div>
                        @endif
                        @endforeach
                    </div>

                    <div class="form-group">
                        <button class="btn-sassy btn-submit">UPDATE</button>
                    </div>
                </div>
            </div>

            {!! Form::close() !!}

            @endif


            <div class="clearfix">
                <div class="data-group group-half">
                    <span class="label">Company</span>
                    <span class="content">{{ $q->company_name }}</span>
                </div>
                <div class="data-group group-half">
                    <span class="label">Team Members</span>
                    <span class="content">{{ $q->members }}</span>
                </div>
            </div>

            <div class="clearfix">
                <div class="data-group group-half">
                    <span class="label">Category</span>
                    <span class="content">{{ $q->textCategory() }}</span>
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
                    <span class="content">{!! $q->textFirstBatch() !!}</span>
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

            @include('hub.q-detail-panel', ['title' => 'Project Brief' , 'content' => $q->scope])
            @include('hub.q-detail-option',['title' => 'Project Requirement' , 'type' => 'involved'])
            @include('hub.q-detail-panel', ['title' => 'Other Requirement' , 'content' => $q->requirements])
            @include('hub.q-detail-panel', ['title' => 'Project Nature' , 'content' => $q->textProjectNature()])
            @include('hub.q-detail-panel', ['title' => 'Additional project information' , 'content' => $q->information])
            @include('hub.q-detail-panel', ['title' => 'Other comments' , 'content' => $q->comments])

            {{-- Project Spec --}}
            <div class="panel panel-default">
                <div class="panel-heading">
                    BOM or list of electronic key components
                </div>
                <div class="panel-body">
                    @if(!$q->key or !$q->key_list)
                    N/A
                    @else
                    {!! $q->key_list !!}
                    @endif
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    Product Diemntions :
			  		<span class="option-dimention">
			  			{!! $q->shape_l !!} mm(L) X {!! $q->shape_w !!} mm(W) X {!! $q->shape_h !!} mm(L)
			  		</span>
                </div>
                <div class="panel-body option-other-dimention">
                    @if(!$q->other_shape)
                    <span class="no-content">No other shape setting</span>
                    @else
                    {!! $q->other_shape !!}
                    @endif
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    Product Weight : <span class="option-weight">{!! $q->weight !!} g</span>
                </div>
                <div class="panel-body option-other-weight">
                    @if(!$q->other_weight)
                    <span class="no-content">No other weight setting</span>
                    @else
                    {!! $q->other_weight !!}
                    @endif
                </div>
            </div>

            @include('hub.q-detail-option', ['title' => 'Main Chipsets' , 'type' => 'chips'])
            @include('hub.q-detail-option', ['title' => 'Connectivity' , 'type' => 'connectivity'])
            @include('hub.q-detail-option', ['title' => 'Interface' , 'type' => 'communication'])
            @include('hub.q-detail-option', ['title' => 'Display' , 'type' => 'display'])
            @include('hub.q-detail-option', ['title' => 'Wireless Display Communication' , 'type' => 'wireless'])
            @include('hub.q-detail-option', ['title' => 'Audio' , 'type' => 'audio'])

            <div class="panel panel-default">
                <div class="panel-heading">Power and Battery</div>
                <div class="panel-body">
                    <div class="row">
                        <div class="option-power col-sm-6">
                            <span class="option-power--type">AC Power</span>
                            <span class="option-power--value">{!! $q->power_1_v !!} V</span>
                            <span class="option-power--value">{!! $q->power_1_a !!} A</span>
                        </div>
                        <div class="option-power col-sm-6">
                            <span class="option-power--type">DC Power</span>
                            <span class="option-power--value">{!! $q->power_2_v !!} V</span>
                            <span class="option-power--value">{!! $q->power_2_a !!} A</span>
                        </div>
                        <div class="option-power col-sm-6">
                            <span class="option-power--type">Battery</span>
                            <span class="option-power--value">{!! $q->power_3_a !!} mAh</span>
                        </div>
                        <div class="option-power col-sm-6">
                            <span class="option-power--type">Wireless Charge</span>
                            <span class="option-power--value">{!! $q->power_4_v !!} V</span>
                            <span class="option-power--value">{!! $q->power_4_a !!} A</span>
                        </div>
                        <div class="option-power col-sm-6">
                            <span class="option-power--type">Other</span>
							<span class="option-power--value">
								@if(!$q->other_power)
									<span class="no-content">No other power setting</span>
								@else
									{!! $q->other_power !!}
								@endif
							</span>
                        </div>
                    </div>

                </div>
            </div>

            @include('hub.q-detail-option', ['title' => 'Sensors' , 'type' => 'sensors'])
            @include('hub.q-detail-option', ['title' => 'Touch' , 'type' => 'touch'])
            @include('hub.q-detail-option', ['title' => 'Mechanical Materials' , 'type' => 'materials'])
            @include('hub.q-detail-option', ['title' => 'Certification & Environmental Requirements' , 'type' => 'cer'])
            @include('hub.q-detail-option', ['title' => 'Operating System' , 'type' => 'os'])
        </div>
    </div>

</div>

@stop


