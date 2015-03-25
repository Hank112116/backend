<div class="panel panel-default">
  	<div class="panel-heading">
  		{!! $title !!}
  	</div>
  	<div class="panel-body">
		@foreach ($q->getOptions($type) as $index => $opt)
		<div class="option-tag btn {!! $q->hasOption($type, $index)? 'btn-primary' : 'btn-default' !!}">
			{!! $opt !!}
		</div>
		@endforeach
		
		@if($q->getOtherOption($type))
		<div class="option-tag btn {!! $q->getOtherOption($type)? 'btn-primary' : 'btn-default' !!}">
			Other : {!! $q->getOtherOption($type) !!}
		</div>
		@endif
	</div>
</div>