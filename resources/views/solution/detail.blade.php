@extends('layouts.master')
@include('layouts.macro')

@section('css')
	<link rel="stylesheet" href="{{ LinkGen::assets('css/solution-detail.css') }}">
@stop

@section('js')
  <script src="{{ LinkGen::assets('js/solution-detail.js') }}"></script>
  <script src="{{ LinkGen::assets('react/solution-gallery.js') }}"></script>
@stop

@section('content')

<div class="page-header">
    <h1>{{ $solution->getTitle() }}</h1>

	<div></div>

    <a href="{!! $solution->getUrl() !!}" target="_blank" class="btn-mini">
    	<i class="fa fa-eye fa-fw"></i>Front
    </a>

    {!! link_to_action('SolutionController@showUpdate', 'EDIT', $solution->getId(), ['class' => 'btn-mini']) !!}
</div>

<div id="solution-detail">
	<div class="row">

		@foreach($solution->getShowcase() as $gallery)
		<div class="col-md-11 col-md-offset-1 solution-gallery">
			@if($gallery['fileUrl'] == $solution->getImage())
				<h2 class="solution-gallery-cover">Cover</h2>
			@endif

			<div id="solution-gallery" class="solution-thumbs"
				 data-mode="display"
				 data-solution-cover="{!! $solution->getImage() !!}"
				 data-solution-gallery='{!! $image_gallery !!}'>
			</div>
        </div>
        @endforeach

		<div class="col-md-10 col-md-offset-1 clearfix">

			<!-- Title -->
			<div class="data-group">
			  	<span class="label">Title</span>
			  	<span class="content">
					<a href="{{$solution->getUrl()}}" target="_blank">
					{{ $solution->getTitle() }}
					</a>
				</span>
			</div>

			<!-- Category, User -->
			<div class="clearfix">
				<div class="data-group group-half">
				  	<span class="label">Category</span>
				  	<span class="content">
				  		{!! $solution->getTextCategory() !!},

				  		<span class="solution-sub-category">
				  		{!! $solution->getTextSubCategory() !!}
				  		</span>
				  	</span>
				</div>

				@include('modules.detail-half-column', [
					'label' => 'User',
					'content' => link_to_action('UserController@showDetail', e($solution->getOwnerFullName()), $solution->getOwnerId())
				])
			</div>

			<!-- User's Country, City -->
			<div class="clearfix">
				@include('modules.detail-half-column', [
					'label' => 'Country City',
					'content' => e($solution->getOwnerLocation())
				])

			</div>

			<!-- Status, Approve Date -->
			<div class="clearfix">
				<div class="data-group group-half">
				  	<span class="label">Status</span>
				  	<span class="content {!! $solution->isOffShelf()? 'off-shelf' : '' !!}">
				  		{!! $solution->getTextStatus() !!}
						@if($solution->isDeleted())
							 | Deleted ({{$solution->getTextDeleteDate()}})
						@endif
				  	</span>
				</div>

				@include('modules.detail-half-column', [
					'label' => 'Approve Date',
					'content' => $solution->getTextApprovedDate()
				])
			</div>

		</div>

		<!-- Summary -->
		@include('modules.detail-panel', [
			'column_title' => 'Summary',
			'column_content' => e($solution->getSummary())
		])
		<!-- End Summary -->

        {{--
		<!-- Description -->
		@include('modules.detail-panel', [
			'column_title' => 'Solution description & specifications',
			'column_content' => Purifier::clean($solution->getDescription())
		])
		<!-- End Description -->
		--}}

        {{--
		<!-- Looking-For Project List -->
		<div class="col-md-10 col-md-offset-1">
			<div class="panel panel-default">
				  <div class="panel-heading">Collaboration Preferred</div>

				  <div class="panel-body">
				  		<h4>Suitable customer stages</h4>

						<ul class="solution-list solution-looking-list">
							@foreach($solution->getTargetProjectStages() as $project_stage)
							<li>
								<i class="icon icon-tag"></i> {!! $project_stage['text'] !!}
							</li>
							@endforeach
						</ul>

				  		<h4>Project categories</h4>

						<ul class="solution-list solution-looking-list">
							@foreach($solution->getTargetProjectCategories() as $category)
							<li>
								<i class="icon {!! $category['key'] ? 'icon-categ-' . $category['key'] : 'icon-tag' !!}"></i>
								{!! $category['text'] !!}
							</li>
							@endforeach
						</ul>
				  </div>
			</div>
		</div>
		<!-- Looking-For Project List -->
        --}}

        {{--
		<!-- Certifications -->
		<div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
				  <div class="panel-heading">This Solution has following certifications</div>

				  <div class="panel-body">
						<ul class="solution-certification-list">
							@foreach($solution->getCertifications() as $certification)
							<li class="solution-certification-logo"><img src="{!! $certification->getMarkImageUrl() !!}" /></li>
							@endforeach
						</ul>

						<ul class="solution-certification-list">
							@foreach($solution->getCustomCertifications() as $cert_text)
							<li class="solution-certification-text">{!! $cert_text !!}</li>
							@endforeach
						</ul>

				  </div>
            </div>
        </div>
        <!-- End Certifications -->
        --}}

        {{--
		<!-- Application Compatibility -->
		@include('modules.detail-panel', [
			'column_title' => 'Solution Applications / Compatibility',
			'column_content' => Purifier::clean($solution->getCompatibility())
		])
		--}}

		<!-- End Application Compatibility -->

        {{--
		<!-- Served customers -->
		<div class="col-md-10 col-md-offset-1">
			<div class="panel panel-default">
				  <div class="panel-heading">Served customers</div>

				  <div class="panel-body">
						<ul class="solution-list served-customer-list">
							@foreach($solution->getServedCustomers() as $customer)
							<li>
								<i class="icon icon-f-expert-only"></i>

								@if($customer['companyUrl'])
								<a href="//{!! $customer['companyUrl'] !!}" target="_blank">
								   {!! $customer['companyName'] !!}
								</a>
								@else
								<a href="//{!! $customer['companyUrl'] !!}" target="_blank">
								   {!! $customer['companyName'] !!}
								</a>
								@endif
							</li>
							@endforeach
						</ul>
				  </div>
			</div>
		</div>
		<!-- End Served customers -->
        --}}
	</div>

	{{-- @include ('solution.detail-project-tags') --}}

</div>

@stop

