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
    @include('user.component.list-internal-description', ['user' => $user, 'memo' => $user->internalUserMemo])
</td>
<td>
    @include('report.component.user-action', ['user' => $user, 'memo' => $user->internalUserMemo])
</td>
<td>
    @include('user.component.list-expertise-tag', ['user' => $user, 'memo' => $user->internalUserMemo])
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