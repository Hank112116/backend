<div class="panel panel-default project">

    <div class="panel-heading">
        <span class="js-order-number"></span>.
        <a href="{!! $project->textFrontLink() !!}" target="_blank">
            Project #{{ $project->project_id }}
        </a> - {!! $project->profile()->text_status !!}
        <button type="button"
                class="btn {!! $project->profile()->is_ongoing ? 'btn-primary' : 'btn-danger' !!} js-feature-edit"
                object="project" rel="{{ $project->project_id }}">
            Edit
        </button>
        <span class='icon icon-remove js-remove'></span>
    </div>

    <div class="panel-body" object="project" rel="{{ $project->project_id }}">
        <div class="row">
            <div class="col-md-9">
                <div class="col-sm-offset-0 row">
                    <div class="col-xs-6 data-group group-half">
                        <span class="label">Title</span>
                        <span class="content">{{ $project->project_title }}</span>
                    </div>
                    <div class="col-xs-6 data-group group-half">
                        <span class="label">Owner</span>
                        <span class="content">
                          {{ $project->user->last_name }} {{ $project->user->user_name }}
                        </span>
                    </div>
                </div>
            </div>
        </div> 

    </div>
</div>

