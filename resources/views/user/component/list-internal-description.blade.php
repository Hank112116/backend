@if ($user->internalUserMemo)
    @if($user->internalUserMemo->description)
        <a href="javascript:void(0)" class="internal-description" rel="{!! $user->user_id !!}" description="{{ $user->internalUserMemo->description }}">
            <i class="fa fa-pencil"></i>{{ mb_strimwidth($user->internalUserMemo->description, 0, 130, mb_substr($user->internalUserMemo->description, 0, 130) . '...') }}
        </a>
    @else
        <a href="javascript:void(0)" class="btn-mini internal-description" rel="{!! $user->user_id !!}" description="{{ $user->internalUserMemo->description }}">Add</a>
    @endif
@else
    <a href="javascript:void(0)" class="btn-mini internal-description" rel="{!! $user->user_id !!}" description="">Add</a>
@endif