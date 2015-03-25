@if(!$is_restricted)
<div class="row">
	<div class="col-md-8 col-md-offset-2">
		<div class="panel panel-default">
		  <div class="panel-heading">Backed Products</div>
		  <div class="panel-body">
			  	<table class="table table-striped">
				    <tr>
				      	<th>#</th>
				      	<th>Title</th>
				      	<th>Baked</th>
				    </tr>

				    @foreach($user->backed_products as $backp)
			      	<tr>
				        <td>{!! $backp->project->project_id !!}</td>
				        <td>{!! link_to_action('ProductController@showDetail',
				        			e($backp->project->project_title), $backp->project->project_id) !!}
				        </td>
				        <td><span class='cash'>{!! HTML::cash($backp->preapproval_total_amount) !!}</span></td>
				     </tr>
				    @endforeach
			  	</table>
		  </div>
		</div>			
	</div>
</div>
@endif