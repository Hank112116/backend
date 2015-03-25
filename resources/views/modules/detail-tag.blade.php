<div class="row">
    <div class="col-md-10 col-md-offset-1">
        <div class="panel panel-default">
            <div class="panel-heading">{!! $column_title !!}</div>
            <div class="panel-body">
                @foreach ($column_tags as $tag)
                    <span class='tag'>{!! $tag !!}</span>
                @endforeach
            </div>
        </div>
    </div>
</div>