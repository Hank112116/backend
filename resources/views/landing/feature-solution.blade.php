<input type="hidden" name="feature[{!! $feature->getEntityId() !!}][block_type]" value="solution">
<input type="hidden" name="feature[{!! $feature->getEntityId() !!}][block_data]" value="{!! $solution->solution_id !!}">
<input type="hidden" name="feature[{!! $feature->getEntityId() !!}][order]" value="" class='js-order'>

<div class="panel panel-default solution">
    <div class="panel-heading">
        {!! link_to_action('SolutionController@showDetail', 
          "SOLUTION #{$solution->solution_id}", $solution->solution_id) !!}
        <span class='icon icon-remove js-remove'></span>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-sm-2 image-column">
              {!! HTML::image($solution->getImagePath(), '', ['class' => 'solution-cover']) !!}  
            </div>  
            <div class="col-md-7">
                <div class="data-group">
                    <span class="label">Title</span>
                    <span class="content">{{ $solution->solution_title }}</span>
                </div>

                <div class="data-group group-half">
                    <span class="label">User</span>
                    <span class="content">{{ e($solution->textUserName()) }}</span>
                </div>

                <div class="data-group group-half">
                    <span class="label">Category</span>
                    <span class="content">{!! $solution->textMainCategory() !!}</span>
                </div>

                <div class="data-group group-half">
                    <span class="label">Status</span>
                    <span class="content">{!! $solution->textStatus() !!}</span>
                </div>

                <div class="data-group group-half">
                    <span class="label">Approve Date</span>
                    <span class="content">{!! HTML::date($solution->approve_time) !!}</span>
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
                @foreach (explode(',', $solution->tags) as $tag)
                    @if($tag)
                      <span class='tag'>{!! $tag !!}</span>
                    @endif
                @endforeach
            </div>    
        </div> 

    </div>
</div>

