@if($responses->count() > 0)
    <span>
        {!! HTML::time($responses->first()->date_added) !!}
    </span>
@endif