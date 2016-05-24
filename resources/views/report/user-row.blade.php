<td>{!! $user->user_id !!}</td>
<td>
    <a href="{!! $user->textFrontLink() !!}" target="_blank">
        {{ $user->textFullName() }}</a>
</td>
@if($is_super_admin && $user->isHWTrekPM())
    <td>{!! $user->textHWTrekPM() !!}</td>
@else
    <td>{!! ($user->isToBeExpert() or $user->isApplyExpert())?"<font color='red'>{$user->textType()}</font>":$user->textType()  !!}</td>
@endif


@if($is_super_admin)
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
    @if($is_super_admin)
        <br/><span class="table--text-light">{!! $user->signup_ip !!}</span>
    @endif
</td>

<td>{!! $user->textEmailVerify() !!}</td>
<td>{!! $user->textActive() !!}</td>
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

    @if($is_super_admin and ($user->isApplyExpert() or $user->isToBeExpert()))
        {!! link_to_action(
                'UserController@showUpdate', 'EDIT & To-Expert',
                $user->user_id, ['class' => 'btn-mini btn-danger']) !!}
    @else
        {!! link_to_action(
                'UserController@showUpdate', 'EDIT',
                $user->user_id, ['class' => 'btn-mini']) !!}
    @endif
</td>