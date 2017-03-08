<div class="panel panel-default solution">
    <div class="panel-heading">
        <span class="js-order-number"></span>.
        <a href="{!! $solution->textFrontLink() !!}" target="_blank">
            {{ $solution->textType() }} #{{ $solution->solution_id }}
        </a> - {!! $solution->textStatus() !!}
        <button type="button"
                class="btn {!! $solution->isOnShelf() ? 'btn-primary' : 'btn-danger' !!} js-feature-edit"
                object="solution" rel="{{ $solution->solution_id }}">
            Edit
        </button>
        <span class='icon icon-remove js-remove'></span>
    </div>
    <div class="panel-body" object="solution" rel="{{ $solution->solution_id }}">
        <div class="row">
            <div class="col-md-9">
                <div class="col-sm-offset-0 row">
                    <div class="col-xs-6 data-group group-half">
                        <span class="label">Title</span>
                        <span class="content">{{ $solution->solution_title }}</span>
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
</div>
