@if ($memo)
    @if($memo->description)
        <a href="javascript:void(0)" class="internal-description" rel="{!! $user->user_id !!}" description="{{ $memo->description }}">
            <i class="fa fa-pencil"></i>{{ mb_strimwidth($memo->description, 0, 130, mb_substr($memo->description, 0, 130) . '...') }}
        </a>
    @else
        <a href="javascript:void(0)" class="btn-mini internal-description" rel="{!! $user->user_id !!}" description="{{ $memo->description }}">Add</a>
    @endif
@else
    <a href="javascript:void(0)" class="btn-mini internal-description" rel="{!! $user->user_id !!}" description="">Add</a>
@endif