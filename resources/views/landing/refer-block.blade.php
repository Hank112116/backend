@include('layouts.macro')

<div id="project_{!! $refer->project_id !!}" class="row js-block">
    <div class="col-sm-10 col-sm-offset-1 block">
        
        <div class="move-up-down">
            <div class="icon icon-sort-up arrow js-move-up"></div>
            <div class="icon icon-sort-down arrow js-move-down"></div>
        </div>

        <input type="hidden" name="refer[{!! $refer->project_id !!}][project_id]" 
                value="{!! $refer->project_id !!}">

        <input type="hidden" name="refer[{!! $refer->project_id !!}][order]" 
                value="{!! $refer->order !!}" class='js-order'>

        <div class="panel panel-default project">
            <div class="panel-heading">
                {!! link_to_action('ProductController@showDetail',
                  "PROJECT #{$project->project_id}", $project->project_id) !!}

                <span class='icon icon-remove js-remove'></span>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-2 image-column">
                      {!! HTML::image($project->getImagePath(), '', ['class' => 'project-cover']) !!}  
                    </div>  
                    <div class="col-md-7">
                        <div class="data-group">
                            <span class="label">Title</span>
                            <span class="content">{{ $project->project_title }}</span>
                        </div>

                        <div class="data-group group-half">
                            <span class="label">User</span>
                            <span class="content">
                              {{ $project->user->last_name }} {{ $project->user->user_name }}
                            </span>
                        </div>

                        <div class="data-group group-half">
                            <span class="label">Category</span>
                            <span class="content">{!! $project->textCategory() !!}</span>
                        </div>

                        <div class="data-group group-half">
                            <span class="label">Goal</span>
                            <span class="content">{!! HTML::cash($project->amount) !!}</span>
                        </div>
                        <div class="data-group group-half">
                            <span class="label">Raised</span>
                            <span class="content">{!! HTML::cash($project->amount_get) !!}</span>
                        </div>
                        <div class="data-group group-half">
                            <span class="label">Status</span>
                            <span class="content">{!! $project->text_status !!}</span>
                        </div>
                        <div class="data-group group-half">
                            <span class="label">Submit</span>
                            <span class="content">{!! $project->text_submit !!}</span>
                        </div>
                        <div class="data-group group-half">
                            <span class="label">Approve Date</span>
                            <span class="content">{!! HTML::date($project->approve_time) !!}</span>
                        </div>
                        <div class="data-group group-half">
                            <span class="label">End Date</span>
                            <span class="content">{!! HTML::date($project->end_date) !!}</span>
                        </div>

                        <div class="input-group route-input">
                            <span class="input-group-addon">Refernce Link</span>
                            <input type="text" name="refer[{!! $refer->project_id !!}][url_project_title]" class="form-control" 
                                   value="{!! $refer->url_project_title !!}">
                            <span class="input-group-addon">
                                https://{!! Config::get('app.front_domain') !!}/reference/{!! $refer->url_project_title !!}
                            </span>
                        </div>
                    </div> 
                    <div class="col-md-3 expertise-column">
                        @foreach (explode(',', $project->tags) as $tag)
                            @if($tag)
                              <span class='tag'>{!! $tag !!}</span>
                            @endif
                        @endforeach
                    </div>    
                </div> 

            </div>
        </div>

    </div>
</div>

