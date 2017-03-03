<div id="project-detail">
	<div class="row">

		<div class="col-md-11 col-md-offset-1">
			@include('modules.update-cover', ['image' => $project->getImagePath()])
		</div>

		<div class="col-md-10 col-md-offset-1 clearfix">
            <div class="clearfix">
                @include('modules.detail-half-column', [
                    'label' => 'Project Title*',
                    'content' => e($project->textTitle())
                ])

                @include('modules.detail-half-column', [
                    'label' => 'Statistics',
                    'content' => 'Community:'. $project->getPageViewCount() .
                    ', Staff Referrals:' . $project->getStaffReferredCount() .
                    ', Collaborators:' . $project->getCollaboratorsCount()

                ])
            </div>
            <div class="clearfix">
                @include('modules.detail-half-column', [
                    'label' => 'Project Category*',
                    'content' => e($project->textCategory())
                ])
                @include('modules.detail-half-column', [
                    'label' => 'Project Type',
                    'content' => $project->textInnovationType()
                ])
            </div>
			<div class="clearfix">
                @include('modules.detail-half-column', [
                    'label' => 'Project Status',
                    'content' => $project->profile()->text_status . ($project->is_deleted? ' | Deleted (' .$project->deleted_date. ')' : '')
                ])

			    @include('modules.detail-half-column', [
			        'label' => 'Owner',
			        'content' => link_to_action('UserController@showDetail',
                                    e($project->user->textFullName()),
                                    $project->user_id
                                )
			    ])
			</div>

			<div class="clearfix">
			    @include('modules.detail-half-column', [
			        'label' => 'Country*',
			        'content' => e($project->project_country)
			    ])

			    @include('modules.detail-half-column', [
			        'label' => 'City*',
			        'content' => e($project->project_city)
			    ])
			</div>

			<div class="clearfix">
			    @include('modules.detail-half-column', [
			        'label' => 'Current Stage (initial launch)*',
			        'content' => $project->textProgress()
			    ])

			    @include('modules.detail-half-column', [
			        'label' => 'Shipping Quantity*',
			        'content' => $project->firstBatchQuantity()
			    ])
			</div>

            <div class="clearfix">
                @include('modules.detail-half-column', [
                    'label' => 'Target Markets*',
                    'content' => $project->textTargetMarkets()
                ])

                @include('modules.detail-half-column', [
                    'label' => 'Development Budget*',
                    'content' => $project->textBudget()
                ])
            </div>

            <div class="clearfix">
			    @include('modules.detail-half-column', [
			        'label' => 'Target Price (MSRP)*',
			        'content' => $project->textMsrp()
			    ])

			    @include('modules.detail-half-column', [
			        'label' => 'Target Shipping Date*',
			        'content' => $project->textLaunchDate()
			    ])
            </div>

            <div class="clearfix">
                @if(empty($project->textCompanyUrl()))
                    @include('modules.detail-half-column', [
                        'label' => 'Company Name*',
                        'content' => $project->textCompanyName() ? $project->textCompanyName() : 'N/A'
                    ])
                @else
                    @include('modules.detail-half-column', [
                        'label' => 'Company Name*',
                        'content' => link_to($project->textCompanyUrl(), $project->textCompanyName() ? $project->textCompanyName() : 'N/A', ['target' => '_blank'])
                    ])
                @endif

                @include('modules.detail-half-column', [
                    'label' => 'Team Size*',
                    'content' => $project->textTeamSize()
                ])
            </div>

            <div class="clearfix">
                @include('modules.detail-half-column', [
                    'label' => 'Created On',
                    'content' => $project->date_added
                ])

                @include('modules.detail-half-column', [
                    'label' => 'Project Last Update',
                    'content' => $project->update_time . ' By ' . link_to($project->lastEditorUser->textFrontLink(), $project->lastEditorUser->textFullName(), ['target' => '_blank'])
                ])
            </div>

            <div class="clearfix">
                @include('modules.detail-half-column', [
                    'label' => 'Kickstarter Campaign',
                    'content' => $project->textKickstarterLink()
                ])

                @include('modules.detail-half-column', [
                    'label' => 'Indiegogo Campaign',
                    'content' => $project->textIndiegogoLink()
                ])
            </div>

		</div>
	</div> 

	<div class="row">
        @include('modules.detail-panel', [
            'column_title' => 'Brief*',
            'column_content' => e($project->project_summary)
        ])

        @include('modules.detail-panel', [
            'column_title' => 'Project Concept*',
            'column_content' => Purifier::clean($project->description)
        ])
	</div>

    @include('modules.detail-tag', [
        'column_title' => 'Team Strengths*',
        'column_tags'  => $project->teamStrengths()
    ])

    @include('modules.detail-tag', [
        'column_title' => 'Resources Required*',
        'column_tags'  => $project->resourceRequirements()
    ])

    <div class="row">
        @include('modules.detail-panel', [
            'column_title' => 'Message to Experts',
            'column_content' => e($project->requirement)
        ])
    </div>

	@include ('project.detail-project-tags')
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            @include ('project.detail-project-spec')
        </div>
    </div>
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            @include ('project.detail-project-attachments')
        </div>
    </div>
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            @include ('project.detail-project-funding', ['funding_rounds' => $project->fundingRounds()])
        </div>
    </div>
</div>



