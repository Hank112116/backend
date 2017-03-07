<input type="hidden" name="feature[{!! $feature->getEntityId() !!}][block_type]" value="expert">
<input type="hidden" name="feature[{!! $feature->getEntityId() !!}][block_data]" value="{!! $user->user_id !!}">
<input type="hidden" name="feature[{!! $feature->getEntityId() !!}][order]" value="" class='js-order'>

<div class="panel panel-default user">
    <div class="panel-heading">
        <span class="js-order-number"></span>.
        <a href="{!! $user->textFrontLink() !!}" target="_blank">
            Expert #{{ $user->user_id }}
        </a> -
        <button type="button" class="btn btn-primary js-feature-edit" object="expert" rel="{{ $user->user_id }}">Edit</button>
        <span class='icon icon-remove js-remove'></span>
    </div>
    
    <div class="panel-body" object="expert" rel="{{ $user->user_id }}">
        <div class="row">
            <div class="col-md-7">
                <div class="col-sm-offset-0 row">
                    <div class="col-xs-6 data-group group-half">
                        <span class="label">Name</span>
                        <span class="content">
                            {{ $user->textFullName() }}
                            @if ($user->isSuspended())
                                ( <font color="red">{{ $user->textStatus() }}</font>)
                            @endif
                        </span>
                    </div>
                    <div class="col-xs-6 data-group group-half">
                        <span class="label">Role</span>
                        <span class="content">{{ $user->textType() }}</span>
                    </div>
                </div>
                <div class="col-sm-offset-0 row">
                    <div class="col-xs-6 data-group group-half">
                        <span class="label">Company</span>
                        <span class="content">{{ $user->company }}</span>
                    </div>
                    <div class="col-xs-6 data-group group-half">
                        <span class="label">Position</span>
                        <span class="content">{{ $user->business_id }}</span>
                    </div>
                </div>
                <div class="col-sm-offset-0 row">
                    <div class="col-xs-6 data-group group-half">
                        <span class="label">Status</span>
                        <span class="content">{{ $user->textStatus() }}</span>
                    </div>
                </div>
            </div>
        </div> 
    </div>
</div>
