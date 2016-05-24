<td>{{ $user->user_id }}</td>
<td>
    @if ($user->isSuspended())
        {{ $user->textFullName() }} ( {{ $user->textStatus() }} )
    @else
        <a href="{!! $user->textFrontLink() !!}" target="_blank">{{ $user->textFullName() }}</a>
    @endif
</td>
<td>{!! ($user->isToBeExpert() && $user->isCreator())?'<font color="red">To Be Expert</font>':$user->textType()  !!}</td>
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
    @include('user.component.list-expertise-tag', ['user' => $user, 'tag_tree' => $tag_tree])
</td>
<td>
    @include('user.component.list-internal-description', ['user' => $user])
</td>
<td>
    @include('report.component.user-action', ['user' => $user])
</td>
<td>
    {!! link_to_action(
            'UserController@showDetail', 'DETAIL',
            $user->user_id, ['class' => 'btn-mini']) !!}
    {!! link_to_action(
            'UserController@showUpdate', 'EDIT',
            $user->user_id, ['class' => 'btn-mini']) !!}
</td>