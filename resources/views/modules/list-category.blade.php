@if($category)
    <i class="icon icon-categ-{!! $category->tag_id !!} icon-category"
        title="{!! $category->tag_name !!}"></i>
@else
    N/A
@endif