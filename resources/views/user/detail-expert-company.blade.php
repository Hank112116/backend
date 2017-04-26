<div class="clearfix">
	<div class="data-group group-half">
	  <span class="label">Company</span>
	  <span class="content">{{ $user->company }}</span>
	</div>

	<div class="data-group group-half">
	  <span class="label">Position</span>
	  <span class="content">{{ $user->business_id }}</span>
	</div>				
</div>

<div class="data-group group-half">
  <span class="label">Company URL</span>
  <span class="content">
  	<a href="{{ $user->getCompanyLink() }}" target="_blank">
  		{{ $user->company_url }}</a>
  </span>
</div>
@if ($user->isExpert() or $user->isToBeExpert() or $user->isApplyExpert())
<div class="data-group group-half">
  <span class="label">Personal URL</span>
  <span class="content">
  	<a href='{{ $user->getPersonalLink() }}' target="_blank">
  		{{ $user->personal_url }}</a>
  </span>
</div>
@endif