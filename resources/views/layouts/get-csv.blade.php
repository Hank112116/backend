<div id="output_all" class='header-output'>
    @if(Auth::user()->isAdmin())
        <a class="btn-mini header-output-link" href="{!! LinkGen::csv() !!}">
            CSV
        </a>

        @if(isset($per_page))
        <a class="btn-mini header-output-link" href="{!! LinkGen::csv($all = true) !!}">
            ALL CSV
        </a>
        @endif
    @endif
</div>