<td>{{ $user->user_id }}</td>
<td>
    @if ($user->isSuspended())
        {{ $user->textFullName() }} ( {{ $user->textStatus() }} )
    @else
        <a href="{!! $user->textFrontLink() !!}" target="_blank">{{ $user->textFullName() }}</a>
    @endif
</td>
<td>{!! ($user->isToBeExpert() && $user->isCreator())?'<span class="color-red">To Be Expert</span>':$user->textType()  !!}</td>
<td>{{ $user->country }}<br/><span class="table--text-light">{{ $user->city }}</span></td>
<td class="table--width-limit">
    {{ $user->company }}<br/>
    <span class="table--text-light">{{ $user->business_id  }}</span>
</td>
<td>
    <span data-time="{!! $user->date_added !!}">{{ $user->textRegistedOn() }}</span>
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
    {!! link_to_action(
            'UserController@showUpdate', 'EDIT',
            $user->user_id, ['class' => 'btn-mini']) !!}
</td>