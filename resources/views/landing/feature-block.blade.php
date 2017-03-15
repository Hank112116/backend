@include('layouts.macro')

<div id="{!! $feature->getEntityBlockId() !!}" class="row js-block">
    <div class="col-sm-10 col-sm-offset-1 block">
        
        <div class="move-up-down">
            <div class="icon icon-sort-up arrow js-move-up"></div>
            <div class="icon icon-sort-down arrow js-move-down"></div>
        </div>
        <div class="panel panel-default user">
            <div class="panel-heading">
                <span class="js-order-number"></span>.
                <a href="{!! $feature->textFrontLink() !!}" target="_blank">
                    {{ $feature->getTextObjectType() }} #{{ $feature->getObjectId() }}
                </a> - {{ $feature->getObjectStatus() }}
                <button type="button"
                        class="btn
                        @if($feature->isDangerStatus())
                                btn-danger
                        @else
                                btn-primary
                        @endif
                                js-feature-edit"
                        object="{{ $feature->getEntityBlockType() }}" rel="{{ $feature->getObjectId() }}">
                    Edit
                </button>
                <span class='icon icon-remove js-remove'></span>
            </div>

            <div class="panel-body" object="{{ $feature->getEntityBlockType() }}" rel="{{ $feature->getObjectId() }}">
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
                                <span class="label">{{ $feature->isUserObject() ? 'Company' : 'Owner' }}</span>
                                <span class="content">{{ e($feature->getObjectOwner()) }}</span>
                            </div>
                        </div>
                    </div>
                </div> 
            </div>
        </div>
    </div>
</div>
