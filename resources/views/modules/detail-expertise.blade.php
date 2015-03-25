<div class="row">
	<div class="col-md-10 col-md-offset-1">
		<div class="panel panel-default">
		  	<div class="panel-heading">{!! $title !!}</div>
		  	<div class="panel-body">
				@foreach ($expertises as $exp)
			   	<div class='expertise-category'>
			   		<p class='category_title hide'>{!! $exp['short_name'] !!}</p>
				    @foreach ($exp['children'] as $tag)
					    @if(in_array($tag['expertise_id'], $expertise_setting))
					    <span class='tag'>{!! $tag['short_name'] !!}</span>
					    @endif
				    @endforeach
			    </div>
				@endforeach
		  	</div>
		</div>			
	</div>
</div>
