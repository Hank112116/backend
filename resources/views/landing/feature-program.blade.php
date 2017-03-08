<div class="panel panel-default program">
    <div class="panel-heading">
        <span class="js-order-number"></span>.
        <a href="{!! $program->textFrontLink() !!}" target="_blank">
            {{ $program->textType() }} #{{ $program->solution_id }}
        </a> - {!! $program->textStatus() !!}
        <button type="button"
                class="btn {!! $program->isOnShelf() ? 'btn-primary' : 'btn-danger' !!} js-feature-edit"
                object="program" rel="{{ $program->solution_id }}">
            Edit
        </button>
        <span class='icon icon-remove js-remove'></span>
    </div>
    <div class="panel-body" object="program" rel="{{ $program->solution_id }}">
        <div class="row">
            <div class="col-md-9">
                <div class="col-sm-offset-0 row">
                    <div class="col-xs-6 data-group group-half">
                        <span class="label">Title</span>
                        <span class="content">{{ $program->solution_title }}</span>
                    </div>
                    <div class="col-xs-6 data-group group-half">
                        <span class="label">Owner</span>
                        <span class="content">{{ e($program->textUserName()) }}</span>
                    </div>
                </div>
            </div>
        </div> 
    </div>
</div>

