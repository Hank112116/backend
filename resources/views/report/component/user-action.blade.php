@if ($user->internalUserMemo)
    @if($user->internalUserMemo->report_action)
        <a href="javascript:void(0)" class="user-report-action" rel="{!! $user->user_id !!}" action="{{ $user->internalUserMemo->report_action }}">
            <i class="fa fa-pencil"></i>{{ mb_strimwidth($user->internalUserMemo->report_action, 0, 130, mb_substr($user->internalUserMemo->report_action, 0, 130) . '...') }}
        </a>
    @else
        <a href="javascript:void(0)" class="btn-mini user-report-action" rel="{!! $user->user_id !!}" action="{{ $user->internalUserMemo->report_action }}">Action</a>
    @endif
@else
    <a href="javascript:void(0)" class="btn-mini user-report-action" rel="{!! $user->user_id !!}" action="">Action</a>
@endif