<div class="panel panel-default project">

    <div class="panel-heading">
        <span class="js-order-number"></span>.
        <a href="{!! $feature->textFrontLink() !!}" target="_blank">
            Project #{{ $feature->getObjectId() }}
        </a> - {!! $feature->getObjectStatus() !!}
        <button type="button"
                class="btn
                @if ($feature->getObjectStatus() == 'expert-only'
                or $feature->getObjectStatus() == 'private')
                        btn-primary
                @else
                        btn-danger
                @endif
                js-feature-edit"
                object="project" rel="{{ $feature->getObjectId() }}">
            Edit
        </button>
        <span class='icon icon-remove js-remove'></span>
    </div>

    <div class="panel-body" object="project" rel="{{ $feature->getObjectId() }}">
        <div class="row">
            <div class="col-md-9">
                <div class="col-sm-offset-0 row">
                    <div class="col-xs-6 data-group group-half">
                        <span class="label">Title</span>
                        <span class="content">{{ $feature->getObjectName() }}</span>
                    </div>
                    <div class="col-xs-6 data-group group-half">
                        <span class="label">Owner</span>
                        <span class="content">
                          {{ e($feature->getObjectOwner()) }}
                        </span>
                    </div>
                </div>
            </div>
        </div> 
    </div>
</div>
