@if($user)
    <a href="{!! $user->textFrontLink() !!}" target="_blank">
        {!! $user->textFullName() !!}
    </a>
@else
    N/A
@endif