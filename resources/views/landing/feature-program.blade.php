<input type="hidden" name="feature[{!! $feature->getEntityId() !!}][block_type]" value="program">
<input type="hidden" name="feature[{!! $feature->getEntityId() !!}][block_data]" value="{!! $program->solution_id !!}">
<input type="hidden" name="feature[{!! $feature->getEntityId() !!}][order]" value="" class='js-order'>

<div class="panel panel-default program">
    <div class="panel-heading">
        {!! link_to_action('SolutionController@showDetail', 
          "PORGRAM #{$program->solution_id}", $program->solution_id) !!}
        <span class='icon icon-remove js-remove'></span>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-sm-2 image-column">
              {!! HTML::image($program->getImagePath(), '', ['class' => 'solution-cover']) !!}  
            </div>  
            <div class="col-md-7">
                <div class="data-group">
                    <span class="label">Title</span>
                    <span class="content">{{ $program->solution_title }}</span>
                </div>

                <div class="data-group group-half">
                    <span class="label">User</span>
                    <span class="content">{{ e($program->textUserName()) }}</span>
                </div>

                <div class="data-group group-half">
                    <span class="label">Category</span>
                    <span class="content">{!! $program->textMainCategory() !!}</span>
                </div>

                <div class="data-group group-half">
                    <span class="label">Status</span>
                    <span class="content">{!! $program->textStatus() !!}</span>
                </div>

                <div class="data-group group-half">
                    <span class="label">Approve Date</span>
                    <span class="content">{!! HTML::date($program->approve_time) !!}</span>
                </div>
                <div class="data-group group-half">
                    <span class="label">Description</span>
                    <span class="content">
                        <textarea rows="4" cols="40" name="feature[{!! $feature->getEntityId() !!}][description]" 
                            value="" maxlength="250"
                            rel="{!! $feature->getEntityId() !!}">{{ $feature->description }}</textarea>
                    </span>
                    <span id="count_{!! $feature->getEntityId() !!}"></span>
                </div>
            </div> 
            <div class="col-md-3 expertise-column">
                @foreach (explode(',', $program->tags) as $tag)
                    @if($tag)
                      <span class='tag'>{!! $tag !!}</span>
                    @endif
                @endforeach
            </div>    
        </div> 

    </div>
</div>

