<div class="clearfix">
	<div class="data-group group-half">
	  <span class="label">Company</span>
	  <span class="content">{!! $user->company !!}</span>
	</div>

	<div class="data-group group-half">
	  <span class="label">Position</span>
	  <span class="content">{!! $user->business_id !!}</span>
	</div>				
</div>

@if (!$is_restricted)

<div class="data-group">
  <span class="label">Company URL</span>
  <span class="content">
  	<a href="{!! $user->getCompanyLink() !!}" target="_blank">
  		{!! $user->company_url !!}</a>
  </span>
</div>
<div class="data-group">
  <span class="label">Personal URL</span>
  <span class="content">
  	<a href='{!! $user->getPersonalLink() !!}' target="_blank">
  		{!! $user->personal_url !!}</a>
  </span>
</div>

@endif