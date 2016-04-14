<!-- Current Development Stage -->
<div class="form-group">
    <label for="progress" class="col-md-3">Current Stage (initial launch)*</label>
    <div class="col-md-5">

        <select class="form-control" id="progress" name="progress">
            @foreach($current_stage_options as $current_stage_id => $current_stage_option)
                <option value="{!! $current_stage_id !!}" {!! $current_stage_id == $project->progress? 'selected' : '' !!}>
                    {!! $current_stage_option !!}
                </option>
            @endforeach
        </select>

    </div>
</div>

<!-- Company Name -->
<div class="form-group">
    <label for="title" class="col-md-3">Company Name*</label>
    <div class="col-md-5">
        <input type="text" class="form-control" id="title" maxlength="55"
               name="company_name" value="{!! htmlspecialchars($project->textCompanyName()) !!}">
    </div>
    <div class="col-md-5"></div>
</div>

<!-- Company Url -->
<div class="form-group">
    <label for="title" class="col-md-3">Company URL</label>
    <div class="col-md-5">
        <input type="text" class="form-control" id="title" maxlength="55"
               name="company_url" value="{!! htmlspecialchars($project->textCompanyUrl()) !!}">
    </div>
    <div class="col-md-5"></div>
</div>

<!-- Team Size -->
<div class="form-group">
    <label for="size" class="col-md-3">Team Size*</label>
    <div class="col-md-9 select-tags" data-select-one = "team-size">
        @foreach($team_size_options as $index => $size)
            <div data-id='{!! $index  !!}'
                 class='btn btn-primary tag {!! $project->textTeamSize() == $index ? 'active' : '' !!}' >
                {!! $size  !!}
            </div>
        @endforeach
        <input type="hidden" name="size" value="{!! $project->textTeamSize()  !!}" />
    </div>
</div>

<!-- Team Strengths -->
<div class="form-group">
    <label for="team" class="col-md-3">Team Strengths*</label>
    <div class="col-md-9">
        <input type="text" id="strengths" name="strengths"
                placeholder="Enter 'team strength' then press [Enter]"
                value="{!! implode(',', $project->teamStrengths()) !!}" />
    </div>
</div>

<!-- Resource Requirement -->
<div class="form-group">
    <label for="team" class="col-md-3">Resource Required*</label>
    <div class="col-md-9">

        <div class="resource-container select-tags" data-select-tags = "resource" >
            @foreach($resource_options as $index => $resource)
               <div data-id='{!! $index  !!}'
                    class='btn btn-primary tag {!! $project->hasResource($index)? 'active' : '' !!}'   >
                   {!! $resource  !!}
               </div>
            @endforeach
            <input type="hidden" name="resource" value="{{ implode(',', $project->getActiveResourcesAttribute()) }}" />
        </div>

        <div class="other-tag">
            <div class="btn btn-primary tag resource-other {!! $project->resource_other? 'active' : '' !!}"
                 data-other-tag = "resource">
                <span class="tag-other">Others</span>
                <input type="text" maxlength="50" name="resource_other" value="{!! $project->resource_other  !!}" />
            </div>
        </div>

    </div>
</div>

<!-- Requirement Criteria -->
<div class="form-group">
    <label for="requirement" class="col-md-3">Message to Experts</label>
    <div class="col-md-5">
        <textarea id="requirement" name="requirement"
            class="form-control" rows="5">{!! $project->requirement !!}</textarea>
    </div>
</div>

<!-- Target Martket -->
<div class="form-group">
    <label for="address" class="col-md-3">Target Markets</label>
    <div class="col-md-5">{!! $project->textTargetMarkets() !!}</div>
    <div class="col-md-5"></div>
</div>

<!-- First Batch Quantity -->
<div class="form-group">
    <label for="quantity" class="col-md-3">Shipping Quantity</label>
    <div class="col-md-9 select-tags" data-select-one = "quantity">
        @foreach($quantity_options as $index => $quantity)
           <div data-id='{!! $index  !!}'
                class='btn btn-primary tag {!! $project->quantity == $index ? 'active' : '' !!}' >
               {!! $quantity  !!}
           </div>
        @endforeach
        <input type="hidden" name="quantity" value="{!! $project->quantity  !!}" />
    </div>
</div>

<!-- First Batch Budget -->
<div class="form-group">
    <label for="budget" class="col-md-3">Development Budget*</label>
    <div class="col-md-9 select-tags" data-select-one = "budget">
        @foreach($budget_options as $index => $budget)
            <div data-id='{!! $index  !!}'
                 class='btn btn-primary tag {!! $project->budget == $index ? 'active' : '' !!}' >
                {!! $budget  !!}
            </div>
        @endforeach
        <input type="hidden" name="budget" value="{!! $project->budget  !!}" />
    </div>
</div>

<!-- Target Price (MSRP) -->
<div class="form-group">
    <label for="msrp" class="col-md-3">Target Price (MSRP)*</label>

    <div class="col-md-4">
        <div class="input-group select-unsure-wrapper" data-select-unsure="msrp">
            <span class="input-group-addon">USD</span>

            <input id="msrp" name="msrp" maxlength="9" class="form-control"
                value="{!! $project->isMsrpUnsure()? '' : $project->msrp !!}" />

            <span data-unsure =''
                class="input-group-addon select-unsure {!!  $project->isMsrpUnsure()? 'active' : '' !!}" >
                <i class="fa fa-power-off"></i> Not Sure
            </span>
        </div>
    </div>
</div>

<!-- Target Shipping Date -->
<div class="form-group">
    <label for="launch_date" class="col-md-3">Target Shipping Date*</label>

    <div class="col-md-4">
        <div class="input-group select-unsure-wrapper" data-select-unsure="shipping-date">
            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>

            <input id="launch_date" name="launch_date" maxlength="9"
                class="form-control" value="{!! $project->launch_date !!}">

            <span data-unsure =''
                  class="input-group-addon select-unsure {!!  $project->isLaunchDateUnsure()? 'active' : '' !!}" >
                <i class="fa fa-power-off"></i> Not Sure
            </span>
        </div>
    </div>
</div>

<!-- Founding Rounds -->
<div class="form-group">
    <label for="funding_rounds" class="col-md-3">Funding Rounds</label>
    <div class="col-md-8">
        @include('project.detail-project-funding', ['funding_rounds' => $project->fundingRounds()])
    </div>
</div>

<!-- Attachments -->
<div class="form-group">
    <label for="funding_rounds" class="col-md-3">Attachments</label>
    <div class="col-md-8">
        @include('project.detail-project-attachments')
    </div>
</div>

<!-- Project Features -->
<div class="form-group">
    <label for="experties" class="col-md-3">Features</label>
    <div class="col-md-9 expertise" data-select-tags="project-tag">
        @include('project.update-project-tags')
        @include('project.detail-project-spec')
    </div>
</div>