<div class="panel panel-default user">
    <div class="panel-heading">
        <span class="js-order-number"></span>.
        <a href="{!! $feature->textFrontLink() !!}" target="_blank">
            {{ $feature->getTextObjectType() }} #{{ $feature->getObjectId() }}
        </a> - {{ $feature->getObjectStatus() }}
        <button type="button"
                class="btn
                @if ($feature->getObjectStatus() == 'verified'
                and ($feature->getObjectType() == 'expert'
                or $feature->getObjectType() == 'premium-expert'))
                        btn-primary
                @else
                        btn-danger
                @endif
                js-feature-edit"
                object="expert" rel="{{ $feature->getObjectId() }}">
            Edit
        </button>
        <span class='icon icon-remove js-remove'></span>
    </div>
    
    <div class="panel-body" object="expert" rel="{{ $feature->getObjectId() }}">
        <div class="row">
            <div class="col-md-9">
                <div class="col-sm-offset-0 row">
                    <div class="col-xs-6 data-group group-half">
                        <span class="label">Name</span>
                        <span class="content">
                            {{ e($feature->getObjectName()) }}
                        </span>
                    </div>
                    <div class="col-xs-6 data-group group-half">
                        <span class="label">Company</span>
                        <span class="content">{{ e($feature->getObjectOwner()) }}</span>
                    </div>
                </div>
            </div>
        </div> 
    </div>
</div>
