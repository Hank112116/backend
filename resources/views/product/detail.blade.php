@extends('layouts.master')
@include('layouts.macro')

@section('css')
    <link rel="stylesheet" href="/css/product-detail.css">
@stop

@section('content')

<div class="page-header">
    <h2>{{ $project->project_title }}</h2>
   
    <div>
        <a href="{!! $project->textFrontLink() !!}" target="_blank" class="btn-mini">
            <i class="fa fa-eye fa-fw"></i>Product Page
        </a>

        @if($project->is_project_submitted)
            {!! link_to_action('ProductController@showProjectDetail', 'Project Detail',
                    $project->project_id, ['class' => 'btn-mini']) !!}
        @endif

        @if(!$project->profile->is_fund_end)
            {!! link_to_action('ProductController@showUpdate', 'EDIT',
                    $project->project_id, ['class' => 'btn-mini']) !!}
        @endif
    </div>

</div>

<div id="project-detail">
	<div class="row">
        <div class="col-md-11 col-md-offset-1">
            @include('modules.update-cover', ['image' => $project->getImagePath()])
        </div>

		<div class="col-md-10 col-md-offset-1 clearfix">

            @include('modules.detail-full-column', [
                'label' => 'Title',
                'content' => e($project->project_title)
            ])

			<div class="clearfix">
                @include('modules.detail-half-column', [
                    'label' => 'Category',
                    'content' => $project->textCategory()
                ])

                @include('modules.detail-half-column', [
                    'label' => 'User',
                    'content' => link_to_action('UserController@showDetail',
                                    e($project->user->textFullName()),
                                 	$project->user_id
                                 )
                ])
			</div>

			<div class="clearfix">
                @include('modules.detail-half-column', [
                    'label' => 'Country',
                    'content' => e($project->project_country)
                ])

                @include('modules.detail-half-column', [
                    'label' => 'City',
                    'content' => e($project->project_city)
                ])
			</div>

			<div class="clearfix">
                <div class="data-group group-half">
                    <span class="label">Goal</span>
                    <span class="content">{!! HTML::cash($project->amount) !!}</span>
                    <span class="tail">USD</span>
                </div>

                <div class="data-group group-half">
                    <span class="label">Raised</span>
                    <span class="content">{!! HTML::cash($project->amount_get) !!}</span>
                    <span class="tail">USD</span>
                </div>
			</div>

            @if($project->profile->is_ongoing)
			<div class="clearfix">
                @include('modules.detail-half-column', [
                    'label' => 'Expert Perking',
                    'content' => $project->profile->textExpertPerking()
                ])

                @include('modules.detail-half-column', [
                    'label' => 'Customer Perking',
                    'content' => $project->profile->textCustomerPerking()
                ])
			</div>
			@endif

			<div class="clearfix">
                @include('modules.detail-half-column', [
                    'label' => 'Status',
                    'content' => $project->profile->text_status
                ])

                @include('modules.detail-half-column', [
                    'label' => 'Submit',
                    'content' => $project->profile->text_product_submit
                ])
			</div>

            <div class="clearfix">
                @include('modules.detail-half-column', [
                    'label' => 'Create Date',
                    'content' => $project->date_added
                ])

                @include('modules.detail-half-column', [
                    'label' => 'Last Update',
                    'content' => $project->update_time
                ])
            </div>

            <div class="clearfix">
                @include('modules.detail-half-column', [
                    'label' => 'Approve Date',
                    'content' => HTML::date($project->approve_time)
                ])

                @if($project->is_postponing)
                    @include('modules.detail-half-column', [
                        'label' => 'Postpond Date',
                        'content' => HTML::date($project->postpond_time)
                    ])
                @endif
            </div>

		</div>
	</div> 

	<div class="row">

		<div class="col-md-10 col-md-offset-1">
			<div class="panel panel-default">
			  	<div class="panel-heading">Video | URL : {!! $project->video !!} </div>
			  	<div class="panel-body">
			  		@if ($project->video)
					<iframe width="560" height="315" frameborder="0" allowfullscreen 
							src="{!! $project->textVideo() !!}">
					</iframe>
					@endif
			  	</div>
			</div>			
		</div>

        @include('modules.detail-panel', [
            'column_title' => 'Summary',
            'column_content' => e($project->project_summary)
        ])

        @include('modules.detail-panel', [
            'column_title' => 'Description',
            'column_content' => Purifier::clean($project->description)
        ])


        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                  <div class="panel-heading">Perks</div>
                  <div class="panel-body perks">@include ('product.detail-perks')</div>
            </div>
        </div>

       <div class="col-md-10 col-md-offset-1">
           <div class="panel panel-default">
                 <div class="panel-heading">Bakers</div>
                 <div class="panel-body perks">@include ('product.detail-bakers')</div>
           </div>
       </div>

    </div>

</div>


@stop

