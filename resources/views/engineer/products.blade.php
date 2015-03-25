@extends('layouts.master')

@section('css')
	<link rel="stylesheet" href="/css/engineer-products.css">
@stop

@section('js')
	<script>
		$(function(){
		  	var $ppc = $('.progress-pie-chart'),
		    	percent = parseInt($ppc.data('percent')),
		    	deg = 360*percent/100;


		  	$('.progress-pie-chart').each(function(kee, block){
		  		var $block = $(block),
		  			percent = parseInt($block.data('percent')),
		    		deg = percent > 100? 360 : 360*percent/100;

			  	if (percent > 50) {
			    	$block.addClass('gt-50');
			  	}

			  	$block.find('.ppc-progress-fill').first().css('transform','rotate('+ deg +'deg)');
			  	$block.find('.ppc-percents span').first().html(percent+'%');

		  	});
		});
	</script>
@stop

@section('content')

@foreach($products as $p)
<div class="col-sm-2 project-block">
	<div class="title">
		{!! $p->project_title !!}
	</div>
	
	<div class="progress-pie-chart" data-percent="{!! ($p->amount_get / $p->amount) * 100 !!}">
	  	<div class="ppc-progress">
	    	<div class="ppc-progress-fill"></div>
	  	</div>

	  	<div class="ppc-percents">
	    	<div class="pcc-percents-wrapper">
	      		<span>%</span>
	    	</div>
	  	</div>			
	</div>

	<div class="perk">
		<span class="icon icon-coin"></span>
		<span>{!! $p->amount_get !!} / {!! $p->amount !!}</span>
	</div>

	<div class="backer">
		<span class="icon icon-user"></span>
		<span>{!! $p->backers !!}</span>
	</div>

	<div class="day">
		{!! Carbon::now()->diffInDays(Carbon::parse($p->end_date)) !!} Days
	</div>
</div>
@endforeach


@stop
