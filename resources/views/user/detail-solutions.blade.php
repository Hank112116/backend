<div class="row">
	<div class="col-md-10 col-md-offset-1">
		<div class="panel panel-default">
		  	<div class="panel-heading">
		  		Solutions
		  	</div>
		  	<div class="panel-body">
				<table class="table table-striped">
				    <tr>
				        <th>#</th>
				        <th class='table-title'>Title</th>
				        <th>Category</th>
				        <th>Country</th>
				        <th>City</th>
				        <th>Status</th>
				        <th>Approve<br/>Date</th>
				        <th>Manager<br/>Approved</th>
                        <th></th>
				    </tr>

				    @foreach($solutions as $solution)
				     <tr>
				        <td>{!! $solution->solution_id !!}</td>
				        <td>
         					<a href="{!! $solution->textFrontLink() !!}" target="_blank">
         						{!! $solution->textTitle() !!}</a>
				        </td>
				        <td>{!! $solution->textMainCategory() !!}, {!! $solution->textSubCategory() !!}</td>
				        <td>{!! $solution->solution_country !!}</td>
				        <td>{!! $solution->solution_city !!}</td>
				        <td>{!! $solution->textStatus() !!}</td>
				        <td>{!! HTML::date($solution->approve_time) !!}</td>
                        <td>
                            @if($solution->is_manager_approved)
                                <i class="fa fa-check"></i>
                            @endif
                        </td>
				        <td>
				            {!! link_to_action(
				                'SolutionController@showDetail', 'DETAIL', 
				                 $solution->solution_id, ['class' => 'btn-mini']) !!}

				            @if($solution->is_wait_approve_draft or $solution->is_wait_approve_ongoing)
				                {!! link_to_action(
				                    'SolutionController@showUpdate', 'EDIT & APPROVE',
				                    $solution->solution_id, ['class' => 'btn-mini btn-danger']) !!}
				            @else
				                {!! link_to_action(
				                    'SolutionController@showUpdate', 'EDIT',
				                    $solution->solution_id, ['class' => 'btn-mini']) !!}
				            @endif
				        </td>
				     </tr>
				    @endforeach
				 </table>
		  	</div>
		</div>			
	</div>
</div>