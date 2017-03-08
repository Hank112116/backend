<div class="panel panel-default user">
    <div class="panel-heading">
        <span class="js-order-number"></span>.
        <a href="{!! $user->textFrontLink() !!}" target="_blank">
            {{ $user->textType() }} #{{ $user->user_id }}
        </a> - {{ $user->textStatus() }}
        <button type="button"
                class="btn {!! $user->textStatus() == 'Active' ? 'btn-primary' : 'btn-danger' !!} js-feature-edit"
                object="expert" rel="{{ $user->user_id }}">
            Edit
        </button>
        <span class='icon icon-remove js-remove'></span>
    </div>
    
    <div class="panel-body" object="expert" rel="{{ $user->user_id }}">
        <div class="row">
            <div class="col-md-9">
                <div class="col-sm-offset-0 row">
                    <div class="col-xs-6 data-group group-half">
                        <span class="label">Name</span>
                        <span class="content">
                            {{ $user->textFullName() }}
                        </span>
                    </div>
                    <div class="col-xs-6 data-group group-half">
                        <span class="label">Company</span>
                        <span class="content">{{ $user->company }}</span>
                    </div>
                </div>
            </div>
        </div> 
    </div>
</div>
