
@foreach($project->perks()->orderBy('is_pro', 'desc')->get() as $perk)
	<div class="panel panel-{!! $perk->is_pro? 'expert' : 'custom' !!}">

	  	<div class="panel-heading clearfix">
	  		<span class='title-id'>#{!! $perk->perk_id !!} </span>
	  		{{ $perk->perk_title }}
	  		<span class='title-date'>Estimated Delivery at {!! $perk->perk_delivery_date !!}</span>
	  	</div>

	  	<div class="panel-body">

  			<div class="col-md-4">
				<ul class="list-group">
				  	<li class="list-group-item">
				  		Perk Amount
				  		<span class="badge">{!! $perk->perk_amount !!}</span>
				  	</li>
				  	<li class="list-group-item">
				  		Perk Total 
				  		<span class="badge">{!! $perk->perk_total<0? 0 : $perk->perk_total !!}</span>
				  	</li>
				  	<li class="list-group-item">
				  		Shiping Fee Us
				  		<span class="badge">{!! $perk->shipping_fee_us !!}</span>
				  	</li>
				  	<li class="list-group-item">
				  		Shiping Fee Intl 
				  		<span class="badge">{!! $perk->shipping_fee_intl !!}</span>
				  	</li>
				  	<li class="list-group-item">
				  		Perk Get
				  		<span class="badge">{!! $perk->perk_get !!}</span>
				  	</li>
				</ul>
  			</div>

  			<div class="col-md-8">
  			    {!! $perk->perk_description !!}
  			</div>
	  	</div>
	</div>
@endforeach
