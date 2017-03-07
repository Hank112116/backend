<input type="hidden" name="feature[{!! $feature->getEntityId() !!}][block_type]" value="program">
<input type="hidden" name="feature[{!! $feature->getEntityId() !!}][block_data]" value="{!! $program->solution_id !!}">
<input type="hidden" name="feature[{!! $feature->getEntityId() !!}][order]" value="" class='js-order'>

<div class="panel panel-default program">
    <div class="panel-heading">
        <span class="js-order-number"></span>.
        <a href="{!! $program->textFrontLink() !!}" target="_blank">
            Program #{{ $program->solution_id }}
        </a> -
        <button type="button" class="btn btn-primary js-feature-edit" object="program" rel="{{ $program->solution_id }}">Edit</button>
        <span class='icon icon-remove js-remove'></span>
    </div>
    <div class="panel-body" object="program" rel="{{ $program->solution_id }}">
        <div class="row">
            <div class="col-md-7">
                <div class="col-sm-offset-0 row">
                    <div class="col-xs-6 data-group">
                        <span class="label">Title</span>
                        <span class="content">{{ $program->solution_title }}</span>
                    </div>
                </div>
                <div class="col-sm-offset-0 row">
                    <div class="col-xs-6 data-group group-half">
                        <span class="label">Status</span>
                        <span class="content">{!! $program->textStatus() !!}</span>
                    </div>
                    <div class="col-xs-6 data-group group-half">
                        <span class="label">Type</span>
                        <span class="content">{{ e($program->textType()) }}</span>
                    </div>
                </div>
                <div class="col-sm-offset-0 row">
                    <div class="col-xs-6 data-group group-half">
                        <span class="label">Owner</span>
                        <span class="content">{{ e($program->textUserName()) }}</span>
                    </div>
                </div>
            </div>
        </div> 
    </div>
</div>

