<div class='query-logs' >
	{!! link_to_action('EngineerController@deleteLog', "DELETE LOG", 
					  '',['class' => 'btn-mini btn-delete']) !!}
</div>

@foreach($logs as $key => $l) 

<div class="panel-group" id="accordion">

  	<div class="panel panel-primary">

	    <div class="panel-heading">
	      	<h4 class="panel-title" data-toggle="collapse" 
	      		data-parent="#accordion" href="#collapse_{!! $key !!}">
      			<span class="method">{!! $l->method !!} </span>
      			{!! $l->route !!} 
				<span class="badge pull-right">{!! $l->total_cost !!}ms</span>
	        </h4>
	    </div>

	    <div id="collapse_{!! $key !!}" class="panel-collapse collapse in">
	      	<div class="panel-body">
	      		<ul>
					@foreach ($l->queries as $q)
						<li>
							<span class="datetime">{!! $q->datetime !!}</span>	 
							<span class="statement">{!! $q->statement !!}</span>	
							<span class="cost badge pull-right">{!! $q->cost !!}</span>
						</li>
					@endforeach		      			
	      		</ul>
	      	</div>
	    </div>

  	</div>

</div>

@endforeach

