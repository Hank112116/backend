<ul>
@foreach($industries as $category_id => $category)
	@if( is_array($category))
    <li>
        <label>{!! key($category) !!}</label>
        <ul>
        	@foreach(array_pop($category) as $subcategory_id => $subcategory)
            <li>         
                {!! Form::checkbox('user_category_ids[]', $subcategory_id, 
                    in_array($subcategory_id, $user_industries), ['id' => "category_{$subcategory_id}"]) !!}
                <label class="iradio-lable" for="{!! "category_{$subcategory_id}" !!}" >{!! $subcategory !!}</label>
            </li>	
        	@endforeach
        </ul>
    </li>	
	@else
    <li>
        {!! Form::checkbox('user_category_ids[]', $category_id, 
            in_array($category_id, $user_industries), ['id' => "category_{$category_id}"]) !!}
        <label class="iradio-lable" for="{!! "category_{$category_id}" !!}" >{!! $category !!}</label>
    </li>			
	@endif
@endforeach
</ul>