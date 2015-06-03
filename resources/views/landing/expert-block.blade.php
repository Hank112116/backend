<div id="" class="row js-block">
    <div class="col-sm-10 col-sm-offset-1 block">

        <div class="panel panel-default user">
            <div class="panel-heading">
                {!! link_to_action('UserController@showDetail', "USER #{$user->user_id}", $user->user_id) !!} | 
                <a href="{!! $user->textFrontLink() !!}" target="_blank">
                    HWTrek Profile
                </a>
                <span class='icon icon-remove js-remove'></span>
            </div>
            
            <div class="panel-body" rel="{!! $user->user_id !!}">
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
                            <span class="label">Company</span>
                            <span class="content">{!! $user->company !!}</span>
                        </div>
                        <div class="data-group group-half">
                            <span class="label">Position</span>
                            <span class="content">{!! $user->business_id !!}</span>
                        </div>
                        <div class="data-group group-half">
                            <span class="label">Description</span>
                            <span class="content"><textarea rows="4" cols="40" 
                                maxlength="250" rel="{!! $user->user_id !!}">{!! $description !!}</textarea></span>
                            <span id="count_{!! $user->user_id !!}"></span>
                        </div>
                    </div>   
                </div> 

            </div>
        </div>
    </div>
</div>
