<!-- Current Development Stage -->
<div class="form-group">
    <label for="progress" class="col-md-3">Current Development Stage</label>
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

<!-- Preliminary Specifications -->
<div class="form-group">
    <label for="preliminary_spec" class="col-md-3">Preliminary Specifications</label>
    <div class="col-md-9">
        <textarea id="preliminary_spec" class='js-editor'
                  name="preliminary_spec">{!! $project->preliminary_spec !!}</textarea>
    </div>
</div>

<!-- Key Components List -->
<div class="form-group">
    <label for="key_component" class="col-md-3">Key Components List</label>
    <div class="col-md-9">
        <input type="text" id="key_component" name="key_component"
               placeholder="Enter 'key component' then press [Enter]"
               value="{!! $project->key_component !!}" />
    </div>
</div>

<!-- Team Strengths -->
<div class="form-group">
    <label for="team" class="col-md-3">Team Strengths</label>
    <div class="col-md-9">
        <input type="text" id="team" name="team"
                placeholder="Enter 'key component' then press [Enter]"
                value="{!! $project->team !!}" />
    </div>
</div>

<!-- Resource Requirement -->
<div class="form-group">
    <label for="team" class="col-md-3">Resource Requirements</label>
    <div class="col-md-9">

        <div class="resource-container select-tags" data-select-tags = "resource" >
            @foreach($resource_options as $index => $resource)
               <div data-id='{!! $index  !!}'
                    class='btn btn-primary tag {!! $project->hasResource($index)? 'active' : '' !!}'   >
                   {!! $resource  !!}
               </div>
            @endforeach
            <input type="hidden" name="resource" value="{!! $project->resource  !!}" />
        </div>

        <div class="other-tag">
            <div class="btn btn-primary tag resource-other {!! $project->resource_other? 'active' : '' !!}"
                 data-other-tag = "resource">
                <span class="tag-other">Other</span>
                <input type="text" maxlength="50" name="resource_other" value="{!! $project->resource_other  !!}" />
            </div>
        </div>

    </div>
</div>

<!-- Requirement Criteria -->
<div class="form-group">
    <label for="requirement" class="col-md-3">Requirement Criteria</label>
    <div class="col-md-5">
        <textarea id="requirement" name="requirement"
            class="form-control" rows="5">{!! $project->requirement !!}</textarea>
    </div>
</div>

<!-- First Batch Quantity -->
<div class="form-group">
    <label for="quantity" class="col-md-3">First Batch Quantity</label>
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

<!-- Target Price (MSRP) -->
<div class="form-group">
    <label for="msrp" class="col-md-3">Target Price (MSRP)</label>

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
    <label for="launch_date" class="col-md-3">Target Shipping Date</label>

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

<!-- Project Tag -->
<div class="form-group">
    <label for="experties" class="col-md-3">Project Tags</label>
    <div class="col-md-9 expertise" data-select-tags="project-tag">
        @include('project.update-project-tags')
    </div>
</div>