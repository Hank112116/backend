<div class="row">
	<div class="col-md-10 col-md-offset-1">
		<div class="panel panel-default">
		  <div class="panel-heading">Projects</div>
		  <div class="panel-body">
			  <table class="table table-striped">
			    <tr>
					<th>#</th>
					<th class="table--name">Title</th>
					<th>Category</th>
					<th>User</th>
					<th>Country<br/><span class="table--text-light">City</span></th>

					<th>Status</th>
					<th>Submit</th>

					<th>Hub</th>
					<th class="table--text-center">Propose</th>
					<th>Last Update</th>
					<th></th>
			    </tr>

			    @foreach($projects as $p)
		      	<tr>
					<td>{!! $p->project_id !!}</td>
					<td class="table--name">
						<a href="{!! $p->textFrontLink() !!}" target="_blank">
							{{ $p->textTitle() }}
						</a>
					</td>

					<td>
						@include('modules.list-category', ['category' => $p->category])
					</td>

					<td>
						<a href="{!! $p->user->textFrontLink() !!}" target="_blank">
							{{ $p->user->textFullName() }}
						</a>
					</td>
					<td>
						{{ $p->project_country }}
						<br/>
						<span class="table--text-light">{{ $p->project_city }}</span>
					</td>

					<td>{!! $p->profile->text_status !!}</td>

					<td>{!! $p->profile->text_project_submit !!}<br/> <!-- project summit date --></td>

					<td>
						@if($p->schedule)
							<i class="fa fa-check"></i>
						@endif
					</td>

					<td class="table--text-center">{!! $p->propose->count() !!}</td>

					<td>{!! HTML::time($p->update_time)  !!}</td>

					<td>
						{!!
                            link_to_action('ProjectController@showDetail', 'DETAIL',
                                $p->project_id, ['class' => 'btn-mini'])
                        !!}

						{!!
                            link_to_action('ProjectController@showUpdate', 'EDIT',
                                $p->project_id, ['class' => 'btn-mini'])
                        !!}
					</td>
		      	</tr>
			    @endforeach
			  </table>
		  </div>
		</div>			
	</div>
</div>
