<div id="project-detail">
	<div class="row">
		<div class="col-md-11 col-md-offset-1">
			@include('modules.update-cover', ['image' => $project->getImagePath()])
		</div>

		<div class="col-md-10 col-md-offset-1 clearfix">

            @include('modules.detail-half-column', [
                'label' => 'Title',
                'content' => e($project->project_title)
            ])

			<div class="clearfix">
			    @include('modules.detail-half-column', [
			        'label' => 'Category',
			        'content' => e($project->textCategory())
			    ])

			    @include('modules.detail-half-column', [
			        'label' => 'User',
			        'content' => link_to_action('UserController@showDetail',
                                    e($project->user->textFullName()),
                                    $project->user_id
                                )
			    ])
			</div>

			<div class="clearfix">
			    @include('modules.detail-half-column', [
			        'label' => 'Country',
			        'content' => e($project->project_country)
			    ])

			    @include('modules.detail-half-column', [
			        'label' => 'City',
			        'content' => e($project->project_city)
			    ])
			</div>

			<div class="clearfix">
			    @include('modules.detail-half-column', [
			        'label' => 'Current Development Stage',
			        'content' => $project->textProgress()
			    ])

			    @include('modules.detail-half-column', [
			        'label' => 'First Batch Quantity',
			        'content' => $project->firstBatchQuantity()
			    ])
			</div>

            <div class="clearfix">
			    @include('modules.detail-half-column', [
			        'label' => 'Target Price (MSRP)',
			        'content' => $project->textMsrp()
			    ])

			    @include('modules.detail-half-column', [
			        'label' => 'Target Shipping Date',
			        'content' => $project->textLaunchDate()
			    ])
            </div>

			<div class="clearfix">
			    @include('modules.detail-half-column', [
			        'label' => 'Status',
			        'content' => $project->profile->text_status . ($project->is_deleted? ' | Deleted (' .$project->deleted_date. ')' : '')
			    ])

			    @include('modules.detail-half-column', [
			        'label' => 'Submit',
			        'content' => $project->profile->text_project_submit
			    ])
			</div>

            <div class="clearfix">
                @include('modules.detail-half-column', [
                    'label' => 'Create Date',
                    'content' => $project->date_added
                ])

                @include('modules.detail-half-column', [
                    'label' => 'Last Update',
                    'content' => $project->update_time
                ])
            </div>

		</div>
	</div> 

	<div class="row">
        @include('modules.detail-panel', [
            'column_title' => 'Brief',
            'column_content' => e($project->project_summary)
        ])

		@include('modules.detail-panel', [
		    'column_title' => 'Preliminary Specifications',
		    'column_content' => $project->preliminary_spec
		])

        @include('modules.detail-panel', [
            'column_title' => 'Description',
            'column_content' => $project->description
        ])
	</div>

    @include('modules.detail-tag', [
        'column_title' => 'Key Components List',
        'column_tags'  => $project->keyComponents()
    ])

    @include('modules.detail-tag', [
        'column_title' => 'Team Strengths',
        'column_tags'  => $project->teamStrengths()
    ])

    @include('modules.detail-tag', [
        'column_title' => 'Resource Requirements',
        'column_tags'  => $project->resourceRequirements()
    ])

    <div class="row">
        @include('modules.detail-panel', [
            'column_title' => 'Requirement Criteria',
            'column_content' => e($project->requirement)
        ])
    </div>

	@include ('project.detail-project-tags')

</div>



