<td xmlns="http://www.w3.org/1999/html">{{ $user->user_id }}</td>
<td>
    @if ($user->isSuspended())
        {{ $user->textFullName() }} ( {{ $user->textStatus() }} )
    @else
        <a href="{!! $user->textFrontLink() !!}" target="_blank">{{ $user->textFullName() }}</a>
    @endif
</td>
@if(Auth::user()->isAdmin())
    <td>{!! $user->textHWTrekPM() !!}<br>
        @if(!$user->isHWTrekPM())
            <span class="user-sub-category">
                <input type="checkbox"  class="change_pm" rel="{!! $user->user_id !!}" title="to-pm"> To PM
            </span>
        @else
            <span class="user-sub-category">
                <input type="checkbox"  class="change_creator" rel="{!! $user->user_id !!}" title="to-creator"> To Creator
            </span>
            <span class="user-sub-category">
                <input type="checkbox"  class="change_expert" rel="{!! $user->user_id !!}" title="to-expert"> To Expert
            </span>
        @endif
    </td>
@endif

@if(Auth::user()->isAdmin() && $user->isHWTrekPM())
    <td>{!! $user->textHWTrekPM() !!}</td>
@else
    <td>
        @if($user->isToBeExpert() or $user->isApplyExpert())
            <font color="red">{{ $user->textType() }}</font>
        @else
            {!! $user->textType() !!}
        @endif

        @if($user->applyExpertMessage->count() > 0)
            <font color="{!! $user->isApplyExpert() ? 'red' : 'black' !!}"><li style="cursor:pointer" class="fa fa-commenting-o fa-fw fa-2x" rel="{!! $user->user_id !!}"></li></font>
        @endif
    </td>
@endif

@if(Auth::user()->isManagerHead() || Auth::user()->isAdmin())
    <td class="table--user-mail">
        {{ $user->email }}
        @if('facebook' === $user->social)
            <i class="fa fa-facebook-square"></i>
        @elseif('linkedin' === $user->social)
            <i class="fa fa-linkedin-square"></i>
        @endif
    </td>
@endif
<td>{{ $user->country }}<br/>{{ $user->city }}</td>

<td class="table--width-limit">
    {{ $user->company }}<br/>
    <span class="table--text-light">{{ $user->business_id  }}</span>
</td>

<td>
    <span data-time="{!! $user->date_added !!}">{{ $user->textRegistedOn() }}</span>
    @if(!$is_restricted)
        <br/><span class="table--text-light">{!! $user->signup_ip !!}</span>
    @endif
</td>

<td>{!! $user->textEmailVerify() !!}</td>
<td>{!! $user->textActive() !!}</td>
<td>
    {{ implode(' / ', $user->getMappingTag($tag_tree, 2)) }} <br/>
    @if ($user->internalUserMemo)
        <span class="table--text-light"> {{ str_replace(',', ' / ', $user->internalUserMemo->tags) }} </span><br/>
        <a href="javascript:void(0)" class="btn-mini internal-tag" rel="{!! $user->user_id !!}" tags="{{ $user->internalUserMemo->tags }}" expertise-tags="{{ implode(' / ', $user->getMappingTag($tag_tree)) }}">Add</a>
    @else
        <a href="javascript:void(0)" class="btn-mini internal-tag" rel="{!! $user->user_id !!}" tags="" expertise-tags="{{ implode(' / ', $user->getMappingTag($tag_tree)) }}">Add</a>
    @endif
</td>
<td>
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
</td>
<td>
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
</td>
<td>
    {!! link_to_action(
            'UserController@showDetail', 'DETAIL',
            $user->user_id, ['class' => 'btn-mini']) !!}

    @if(!$is_restricted and ($user->isApplyExpert() or $user->isToBeExpert()))
        {!! link_to_action(
                'UserController@showUpdate', 'EDIT & To-Expert',
                $user->user_id, ['class' => 'btn-mini btn-danger']) !!}
    @else
        {!! link_to_action(
                'UserController@showUpdate', 'EDIT',
                $user->user_id, ['class' => 'btn-mini']) !!}
    @endif
</td>