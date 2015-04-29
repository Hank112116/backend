
<!-- Image Gallery -->
<div class="form-group">

    <div class="col-md-3">
        <label for="cover">Cover Gallery</label>
    </div>

    <div class="col-md-8">
        Please upload image with 16:10 ratio, or the thumb image will be transform automatically
    </div>
</div>


<div class="row">
    <div id="solution-gallery" class="solution-thumbs"
         data-solution-cover="{!! $solution->image !!}"
         data-solution-gallery='{!! $solution->image_gallery !!}'>
    </div>
</div>
<!-- End Image Gallery -->

{{--
<!-- is_deleted -->
<div class="form-group">
    <label for="active" class="col-md-3">Delete</label>
    <div class="col-md-5">
        <div>
            {!! Form::radio('is_deleted', '0', $solution->is_deleted==0, ["id"=>"delete_n"]) !!}
            <label for="delete_n" class='iradio-lable'>N</label>
        </div>

        <div>
            {!! Form::radio('is_deleted', '1', $solution->is_deleted==1, ["id"=>"delete_y"]) !!}
            <label for="delete_y" class='iradio-lable'>Y</label>
        </div>
    </div>
</div>
--}}


<!-- Solution Owner Switch Block -->
<div class="form-group">
    <label for="member" class="col-md-3">Solution Owner</label>

    <div id="owner-selector" class="col-md-5"
         data-user = '{!! $solution->user->toBasicJson() !!}'>
    </div>
</div>

<!-- Category -->
<div id="category-wrapper" class="form-group">

    <label for="category" class="col-md-3">Category</label>

    <div class="col-md-4 category-list-wrapper">
        <div id="main-category" class="selected-category">

        </div>

        <ul id='main-category-options' class="category-options">
            @foreach($category_options['main'] as $mc)
            <li id="main-category-{!! $mc['main_id'] !!}-{!! $mc['sub_id']  !!}"
                    data-main-category-id="{!! $mc['main_id'] !!}"
                    data-sub-category-id="{!! $mc['sub_id'] !!}">{!! $mc['text'] !!}</li>
            @endforeach
        </ul>
    </div>

    <div class="col-md-4 category-list-wrapper">
        <div id="sub-category" class="selected-category"></div>

        <ul id='sub-category-options' class="category-options">
        @foreach($category_options['sub'] as $main_category_id => $sub_categories)
            @foreach($sub_categories as $sub_category_id => $sub_category_text)
                <li id="sub-category-{{$main_category_id}}-{!! $sub_category_id  !!}"
                    data-sub-category-id="{!! $sub_category_id !!}"
                    data-main-category-id="{!! $main_category_id !!}">{{$sub_category_text}}</li>
            @endforeach
        @endforeach
        </ul>
    </div>

    <input type="hidden" name="solution_type"   value="{!! $solution->solution_type !!}" />
    <input type="hidden" name="solution_detail" value="{!! $solution->solution_detail !!}" />
</div>
<!-- End Category -->


<!-- Title -->
<div class="form-group">
    <label for="title" class="col-md-3">Title</label>
    <div class="col-md-5">
        <input type="text" class="form-control" id="title" maxlength="55"
            name="solution_title" value="{!! htmlspecialchars($solution->solution_title) !!}">
    </div>
    <div class="col-md-5"></div>
</div>
<!-- End Title -->


<!-- Summary -->
<div class="form-group">
    <label for="solution_summary" class="col-md-3">Summary</label>
    <div class="col-md-5">
        <textarea id="solution_summary" name="solution_summary"
                  maxlength="150" class="form-control" rows="5">{!! $solution->solution_summary !!}</textarea>
    </div>
</div>
<!-- End Summary -->


<!-- Description -->
<div class="form-group">
    <label for="description" class="col-md-3">Solution description & specifications</label>
    <div class="col-md-9">
        <textarea id="description" class='js-editor' name="description">{!! $solution->description !!}</textarea>
    </div>
</div>
<!-- End Description -->

<!-- Looking For Target -- Project-Stage -->
<div class="form-group">

    <label for="target-stage" class="col-md-3">Suitable customer stages</label>

    <div class="col-md-9">

        <div class="target-stage-container select-tags" data-select-tags = "project_progress" >
            @foreach($project_progress_options as $index => $progress)
               <div data-id='{!! $index  !!}'
                    class='btn btn-primary tag {!! $solution->hasProjectProgress($index)? "active" : "" !!}'   >
                   {!! $progress  !!}
               </div>
            @endforeach
            <input type="hidden" name="business_prospect_reference" value="{!! $solution->business_prospect_reference  !!}" />
        </div>

        <div class="other-tag">
            <div class="btn btn-primary tag project-progress-other {!! $solution->business_prospect_reference_other? 'active' : '' !!}"
                 data-other-tag = "project_progress">
                <span class="tag-other">Other (30)</span>
                <input type="text" maxlength="30" name="business_prospect_reference_other" value="{!! $solution->business_prospect_reference_other  !!}" />
            </div>
        </div>

    </div>
</div>
<!-- End Looking For Target -- Project-Stage -->


<!-- Looking For Target -- Project-Category -->
<div class="form-group">

    <label for="target-stage" class="col-md-3">Suitable project categories</label>

    <div class="col-md-9">

        <div class="target-category-container select-tags" data-select-tags = "project_category" >

            @foreach($project_category_options as $index => $category)
               <div data-id='{!! $index  !!}'
                    class='btn btn-primary tag {!! $solution->hasProjectCategory($index)? 'active' : '' !!}' >{!!
                    $category
               !!}</div>
            @endforeach

            <input type="hidden" name="project_category_co_work" value="{!! $solution->project_category_co_work  !!}" />
        </div>

        <div class="other-tag">
            <div class="btn btn-primary tag project-category-other {!! $solution->project_category_co_work_other? 'active' : '' !!}"
                 data-other-tag = "project_category">
                <span class="tag-other">Other (30)</span>
                <input type="text" maxlength="30" name="project_category_co_work_other"
                       value="{!! $solution->project_category_co_work_other  !!}" />
            </div>
        </div>

    </div>
</div>
<!-- End Looking For Target -- Project-Category -->


<!-- Certification -->
<div class="form-group">
    <label for="certification_other" class="col-md-3">Certifications</label>


    <div class="col-md-9">

        <div class="target-stage-container select-tags" data-select-tags = "certification" >
            @foreach($certification_options as $index => $certification_image)
               <div data-id='{!! $index  !!}'
                    class='btn btn-primary tag tag-image {!! $solution->hasCertification($index)? 'active' : '' !!}'   >
                   <img src="{!! $certification_image  !!}" alt=""/>
               </div>
            @endforeach

            <input type="hidden" name="solution_obtained" value="{!! $solution->solution_obtained  !!}" />
        </div>

    </div>


    <div class="col-md-9 col-md-offset-3">
        <input type="text" id="certification_other" name="solution_obtained_other"
                placeholder="Enter 'Certification' then press [Enter]"
                value="{!! $solution->solution_obtained_other !!}" />
    </div>
</div>
<!-- End Certification -->


<!-- Application Compatibility -->
<div class="form-group">
    <label for="application_compatibility" class="col-md-3">Solution Applications / Compatibility</label>
    <div class="col-md-9">
        <textarea id="application_compatibility" class='js-editor' name="solution_application_compatibility">{!!
        $solution->solution_application_compatibility
        !!}</textarea>
    </div>
</div>
<!-- End Application Compatibility -->


<!-- Customer Portfolio -->
<div id='customers-wrapper' class="form-group">
    <label class="col-md-3">
        Customer Portfolio
        <a id="add-customer" class="btn-mini btn-flat-green">ADD <i class="fa fa-plus"></i></a>
    </label>

    <div class="col-md-9">
        <div id="customers" class="customers-wrapper" data-customers = '{!! json_encode($solution->servedCustomers()) !!}'>

        </div>
    </div>

    <!-- Customer Template -->
    <div class="hide customer-template">
        <div class="customer js-customer-block">
            <input type="text" class='customer-customer-name js-customer-name' value="" placeholder="Customer name" />
            <input type="text" class='customer-customer-url js-customer-url'  value="" placeholder="Customer url" />
            <div class="customer-delete js-customer-delete"><i class="fa fa-trash-o"></i></div>
        </div>
    </div>

    <input type="hidden" name="customer_portfolio" value='' />

</div>
<!-- End Customer Portfolio -->

<!-- Project Tag -->
<div class="form-group">
    <label for="experties" class="col-md-3">Project Tags</label>
    <div class="col-md-9 expertise" data-select-tags="project-tag">
        @include('solution.update-project-tags')
    </div>
</div>
