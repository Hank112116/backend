
<div class="row">
	<div class="col-md-10 col-md-offset-1">
		<div class="panel panel-default">
		  <div class="panel-heading">Products</div>
		  <div class="panel-body">
			  <table class="table table-striped">
			    <tr>
			      	<th>#</th>
			      	<th class='table-title'>Title</th>
			      	<th>Category</th>
			      	<th>Country</th>
			      	<th>City</th>
			      	<th>Goal</th>
			      	<th>Raised</th>
			      	<th>Status</th> 
			      	<th>Submit Status</th> 
			      	<th>Approve Date</th>
                    <th>Hub</th>
			      	<th></th>
			    </tr>

			    @foreach($products as $p)
		      	<tr>
			        <td>{!! $p->project_id !!}</td>
			        <td>
         				<a href="{!! $p->textFrontLink() !!}" target="_blank">
         					{!! $p->textTitle() !!}
         				</a>
					</td>
			        <td>{!! $p->category? $p->category->tag_name : '' !!}</td>
			        <td>{!! $p->project_country !!}</td>
			        <td>{!! $p->project_city !!}</td>

			        <td><span class='cash'>{!! HTML::cash($p->amount) !!}</span></td>
			        <td><span class='cash'>{!! HTML::cash($p->amount_get) !!}</span></td>

			        <td>{!! $p->profile->text_status !!}</td> {{-- status --}}
			        <td>{!! $p->profile->text_product_submit !!}</td>
			        {{-- submit status --}}

			        <td>{!! HTML::date($p->approve_time) !!}</td>
                    <td>
                        @if($p->schedule)
                            <i class="fa fa-check"></i>
                        @endif
                    </td>
			        <td>
			            {!! link_to_action(
			                'ProductController@showDetail', 'DETAIL',
			                 $p->project_id, ['class' => 'btn-mini']) !!}
			            
			            @if($p->profile->is_wait_approve_product or
                            $p->profile->is_wait_approve_ongoing)
                            
			                {!! link_to_action(
			                    'ProductController@showUpdate', 'EDIT & APPROVE',
			                    $p->project_id, ['class' => 'btn-mini btn-danger']) !!}
			            @else
			                {!! link_to_action(
			                    'ProductController@showUpdate', 'EDIT',
			                    $p->project_id, ['class' => 'btn-mini']) !!}
			            @endif
			        </td>
		      	</tr>
			    @endforeach
			  </table>
		  </div>
		</div>			
	</div>
</div>
