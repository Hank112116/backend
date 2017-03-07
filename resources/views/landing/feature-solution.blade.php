<input type="hidden" name="feature[{!! $feature->getEntityId() !!}][block_type]" value="solution">
<input type="hidden" name="feature[{!! $feature->getEntityId() !!}][block_data]" value="{!! $solution->solution_id !!}">
<input type="hidden" name="feature[{!! $feature->getEntityId() !!}][order]" value="" class='js-order'>

<div class="panel panel-default solution">
    <div class="panel-heading">
        <span class="js-order-number"></span>.
        <a href="{!! $solution->textFrontLink() !!}" target="_blank">
            Solution #{{ $solution->solution_id }}
        </a> -
        <button type="button" class="btn btn-primary js-feature-edit" object="solution" rel="{{ $solution->solution_id }}">Edit</button>
        <span class='icon icon-remove js-remove'></span>
    </div>
    <div class="panel-body" object="solution" rel="{{ $solution->solution_id }}">
        <div class="row">
            <div class="col-md-7">
                <div class="col-sm-offset-0 row">
                    <div class="col-xs-6 data-group">
                        <span class="label">Title</span>
                        <span class="content">{{ $solution->solution_title }}</span>
                    </div>
                </div>
                <div class="col-sm-offset-0 row">
                    <div class="col-xs-6 data-group group-half">
                        <span class="label">Status</span>
                        <span class="content">{!! $solution->textStatus() !!}</span>
                    </div>
                    <div class="col-xs-6 data-group group-half">
                        <span class="label">Type</span>
                        <span class="content">{{ e($solution->textType()) }}</span>
                    </div>
                </div>
                <div class="col-sm-offset-0 row">
                    <div class="col-xs-6 data-group group-half">
                        <span class="label">Owner</span>
                        <span class="content">{{ e($solution->textUserName()) }}</span>
                    </div>
                </div>
            </div>
        </div> 
    </div>
</div>

