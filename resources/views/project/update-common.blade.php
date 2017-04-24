
{{--
<!-- is_deleted -->
<div class="form-group">
    <label for="active" class="col-md-3">Delete</label>
    <div class="col-md-5">
        <div>
            {!! Form::radio('is_deleted', '0', $project->is_deleted==0, ["id"=>"delete_n"]) !!}
            <label for="delete_n" class='iradio-lable'>N</label>
        </div>

        <div>
            {!! Form::radio('is_deleted', '1', $project->is_deleted==1, ["id"=>"delete_y"]) !!}
            <label for="delete_y" class='iradio-lable'>Y</label>
        </div>
    </div>
</div>
--}}

<!-- Project Cover Art -->
<div class="form-group">
    <label for="cover" class="col-md-3">Project Image*</label>
    <div class="col-md-5">
        {!! HTML::image($project->getImagePath(), '', ['class' => 'cover']) !!}
        <input type="file" id="cover" name="cover">
    </div>
</div>

<!-- Project Owner -->
<div class="form-group">
    <label for="member" class="col-md-3">Project Owner*</label>
    @if(auth()->user()->isManagerHead() || auth()->user()->isAdmin())
    <div id="owner-selector" class="col-md-5" 
         data-user='{!! $project->user->toBasicJson() !!}'>
    </div>
    @else
        <div class="col-md-5">
            <a href="{{ $project->user->textFrontLink() }}" target="_blank"> {{ $project->user->textFullName() }}</a>
        </div>
    @endif
</div>

<!-- Project Category -->
<div class="form-group">
    <label for="category" class="col-md-3">Project Category*</label>
    <div class="col-md-5">
        <select class="form-control" id="category" name="category_id">
            @foreach($category_options as $category_id => $category_option)
            <option value="{!! $category_id !!}" {!! $category_id == $project->category_id? 'selected' : '' !!} >
                {!! $category_option !!}
            </option>
            @endforeach
        </select>
    </div>
</div>

<!-- Type Innovation -->
<div class="form-group">
    <label for="innovation-type" class="col-md-3">Project Type*</label>
    <div class="col-md-5">
        <select class="form-control" id="innovation-type" name="innovation_type">
            @foreach($innovation_options as $index => $innovation_option)
                <option value="{!! $index !!}" {!! $index == $project->innovation_type? 'selected' : '' !!} >
                    {!! $innovation_option !!}
                </option>
            @endforeach
        </select>
    </div>
</div>

<!-- Project Name -->
<div class="form-group">
    <label for="title" class="col-md-3">Project Title*</label>
    <div class="col-md-5">
        <input type="text" class="form-control" id="title" maxlength="55"
               name="project_title" value="{{ $project->project_title }}">
    </div>
    <div class="col-md-5"></div>
</div>

<!-- Project Brief -->
<div class="form-group">
    <label for="project_summary" class="col-md-3">Brief*</label>
    <div class="col-md-5">
        <textarea id="project_summary" name="project_summary" maxlength="150"
            class="form-control" rows="5">{{ $project->project_summary }}</textarea>
    </div>
</div>

<!-- Project Location -->
<div class="form-group">
    <label for="address" class="col-md-3">Project Location*</label>
    <div class="col-md-5">{{ $project->project_address }}</div>
    <div class="col-md-5"></div>
</div>

<!-- Project Location : Country -->
<div class="form-group">
    <label for="country" class="col-md-3">Country*</label>
    <div class="col-md-5">
        <input type="text" class="form-control" id="country"
            name="project_country" value="{{ $project->project_country }}">
    </div>
    <div class="col-md-5"></div>
</div>

<!-- Project Location : City -->
<div class="form-group">
    <label for="city" class="col-md-3">City*</label>
    <div class="col-md-5">
        <input type="text" class="form-control" id="city"
            name="project_city" value="{{ $project->project_city }}">
    </div>
    <div class="col-md-5"></div>
</div>

<!-- KickStarter -->
<div class="form-group">
    <label for="kickstarter" class="col-md-3">Kickstarter Campaign</label>
    <div class="col-md-5">{{ $project->textKickstarterLink() }}</div>
</div>

<!-- Indiegogo -->
<div class="form-group">
    <label for="indiegogo" class="col-md-3">Indiegogo Campaign</label>
    <div class="col-md-5">{{ $project->textIndiegogoLink() }}</div>
</div>

<!-- Design Concept -->
<div class="form-group">
    <label for="description" class="col-md-3">Project Concept*</label>
    <div class="col-md-9">
        <textarea id="description" class='js-editor' name="description">{{ htmlspecialchars($project->description) }}</textarea>
    </div>
</div>
