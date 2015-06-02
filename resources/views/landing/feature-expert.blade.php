<input type="hidden" name="feature[{!! $feature->getEntityId() !!}][block_type]" value="expert">
<input type="hidden" name="feature[{!! $feature->getEntityId() !!}][block_data]" value="{!! $user->user_id !!}">
<input type="hidden" name="feature[{!! $feature->getEntityId() !!}][order]" value="" class='js-order'>

<div class="panel panel-default user">
    <div class="panel-heading">
        {!! link_to_action('UserController@showDetail', "USER #{$user->user_id}", $user->user_id) !!} | 
        <a href="{!! $user->textFrontLink() !!}" target="_blank">
            HWTrek Profile
        </a>
        <span class='icon icon-remove js-remove'></span>
    </div>
    
    <div class="panel-body">
        <div class="row">
            <div class="col-sm-2 image-column">
                {!! HTML::image($user->getImagePath(), 'member-thumb', ['class' => 'user-avatar']) !!}
            </div> 
            <div class="col-md-7">
                <div class="data-group group-half">
                    <span class="label">Name</span>
                    <span class="content">{!! $user->last_name !!} {!! $user->user_name !!}</span>
                </div>
                <div class="data-group group-half">
                    <span class="label">EMail</span>
                    <span class="content">{!! $user->email !!}</span>
                </div>
                <div class="data-group group-half">
                    <span class="label">Country</span>
                    <span class="content">{!! $user->country !!}</span>
                </div>
                <div class="data-group group-half">
                    <span class="label">City</span>
                    <span class="content">{!! $user->city !!}</span>
                </div>
                <div class="data-group group-half">
                    <span class="label">Company</span>
                    <span class="content">{!! $user->company !!}</span>
                </div>
                <div class="data-group group-half">
                    <span class="label">Position</span>
                    <span class="content">{!! $user->business_id !!}</span>
                </div>
                <div class="data-group group-half">
                    <span class="label">Description</span>
                    <span class="content">
                        <textarea rows="4" cols="40" name="feature[{!! $feature->getEntityId() !!}][description]" 
                            value="" maxlength="250"
                            rel="{!! $feature->getEntityId() !!}" >{!! $feature->description !!}</textarea>
                    </span>
                    <span id="count_{!! $feature->getEntityId() !!}"></span>
                </div>
            </div> 
            <div class="col-md-3 expertise-column">
                @foreach (explode(',', $user->tags) as $tag)
                    <span class='tag'>{!! $tag !!}</span>
                @endforeach
            </div>    
        </div> 

    </div>
</div>
