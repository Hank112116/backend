<a href="javascript:void(0)" title="PM Expert Referrals" class="project_recommend" recommend="internal" rel="{{ $project->project_id }}">PM:{{ $recommend_expert->internal_count }}</a><br/>
<a href="javascript:void(0)" title="User Requests and Referrals" class="project_recommend" recommend="external" rel="{{ $project->project_id }}">Users:{{ $recommend_expert->external_count }}</a><br/>
total: {{$recommend_expert->total_count }}