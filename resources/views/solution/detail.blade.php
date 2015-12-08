@extends('layouts.master')
@include('layouts.macro')

@section('css')
	<link rel="stylesheet" href="/css/solution-detail.css">
@stop

@section('js')
  <script src='/js/solution-detail.js'></script>
  <script type="text/javascript" src="/react/solution-gallery.js"></script>
@stop

@section('content')

<div class="page-header">
    <h1>{{ $solution->solution_title }}</h1>

	<div></div>

    <a href="{!! $solution->textFrontLink() !!}" target="_blank" class="btn-mini">
    	<i class="fa fa-eye fa-fw"></i>Front
    </a>

    {!! link_to_action('SolutionController@showUpdate', 'EDIT', $solution->solution_id, ['class' => 'btn-mini']) !!}
</div>

<div id="solution-detail">
	<div class="row">

		@foreach($solution->galleries() as $gallery)
		<div class="col-md-11 col-md-offset-1 solution-gallery">
			@if($gallery['fileName'] == $solution->image)
				<h2 class="solution-gallery-cover">Cover</h2>
			@endif

			<div id="solution-gallery" class="solution-thumbs"
				 data-mode="display"
				 data-solution-cover="{!! $solution->image !!}"
				 data-solution-gallery='{!! $image_gallery !!}'>
			</div>
        </div>
        @endforeach

		<div class="col-md-10 col-md-offset-1 clearfix">

			<!-- Title -->
			<div class="data-group">
			  	<span class="label">Title</span>
			  	<span class="content">{{ $solution->textTitle() }}</span>
			</div>

			<!-- Category, User -->
			<div class="clearfix">
				<div class="data-group group-half">
				  	<span class="label">Category</span>
				  	<span class="content">
				  		{!! $solution->textMainCategory() !!},

				  		<span class="solution-sub-category">
				  		{!! $solution->textSubCategory() !!}
				  		</span>
				  	</span>
				</div>

				@include('modules.detail-half-column', [
					'label' => 'User',
					'content' => link_to_action('UserController@showDetail', e($solution->user->textFullName()), $solution->user_id)
				])
			</div>

			<!-- User's Country, City -->
			<div class="clearfix">
				@include('modules.detail-half-column', [
					'label' => 'Country',
					'content' => e($solution->user->country)
				])

				@include('modules.detail-half-column', [
					'label' => 'City',
					'content' => e($solution->user->city)
				])
			</div>

			<!-- Status, Approve Date -->
			<div class="clearfix">
				<div class="data-group group-half">
				  	<span class="label">Status</span>
				  	<span class="content {!! $solution->isOffShelf()? 'off-shelf' : '' !!}">
				  		{!! $solution->textStatus() !!}
						@if($solution->is_deleted)
							 | Deleted ({{$solution->deleted_date}})
						@endif
				  	</span>
				</div>

				@include('modules.detail-half-column', [
					'label' => 'Approve Date',
					'content' => HTML::date($solution->approve_time)
				])
			</div>

		</div>	

		<!-- Summary -->
		@include('modules.detail-panel', [
			'column_title' => 'Summary',
			'column_content' => e($solution->solution_summary)
		])
		<!-- End Summary -->

		<!-- Description -->
		@include('modules.detail-panel', [
			'column_title' => 'Solution description & specifications',
			'column_content' => Purifier::clean($solution->description)
		])
		<!-- End Description -->

		<!-- Looking-For Project List -->
		<div class="col-md-10 col-md-offset-1">
			<div class="panel panel-default">
				  <div class="panel-heading">Collaboration Preferred</div>

				  <div class="panel-body">
				  		<h4>Suitable customer stages</h4>

						<ul class="solution-list solution-looking-list">
							@foreach($solution->lookingProjectProgresses() as $progress_text)
							<li>
								<i class="icon icon-tag"></i> {!! $progress_text !!}
							</li>
							@endforeach
						</ul>

				  		<h4>Project categories</h4>

						<ul class="solution-list solution-looking-list">
							@foreach($solution->lookingProjectCategories() as $category)
							<li>
								<i class="icon {!! $category['id'] ? 'icon-categ-' . $category['id'] : 'icon-tag' !!}"></i>
								{!! $category['text'] !!}
							</li>
							@endforeach
						</ul>
				  </div>
			</div>
		</div>
		<!-- Looking-For Project List -->


		<!-- Certifications -->
		<div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
				  <div class="panel-heading">This Solution has following certifications</div>

				  <div class="panel-body">
						<ul class="solution-certification-list">
							@foreach($solution->certifications() as $cert_image)
							<li class="solution-certification-logo"><img src="{!! $cert_image !!}" /></li>
							@endforeach
						</ul>

						<ul class="solution-certification-list">
							@foreach($solution->certificationOthers() as $cert_text)
							<li class="solution-certification-text">{!! $cert_text !!}</li>
							@endforeach
						</ul>

				  </div>
            </div>
        </div>
        <!-- End Certifications -->


		<!-- Application Compatibility -->
		@include('modules.detail-panel', [
			'column_title' => 'Solution Applications / Compatibility',
			'column_content' => Purifier::clean($solution->solution_application_compatibility)
		])
		<!-- End Application Compatibility -->

		<!-- Served customers -->
		<div class="col-md-10 col-md-offset-1">
			<div class="panel panel-default">
				  <div class="panel-heading">Served customers</div>

				  <div class="panel-body">
						<ul class="solution-list served-customer-list">
							@foreach($solution->servedCustomers() as $customer)
							<li>
								<i class="icon icon-f-expert-only"></i>

								@if($customer['url'])
								<a href="//{!! $customer['url'] !!}" target="_blank">
								   {!! $customer['name'] !!}
								</a>
								@else
								<a href="//{!! $customer['url'] !!}" target="_blank">
								   {!! $customer['name'] !!}
								</a>
								@endif
							</li>
							@endforeach
						</ul>
				  </div>
			</div>
		</div>
		<!-- End Served customers -->


	</div>
	
	@include ('solution.detail-project-tags')

</div>

@stop

