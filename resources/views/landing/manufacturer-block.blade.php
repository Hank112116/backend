<div class="col-sm-6 block">
    <span class="icon icon-remove"></span>

    <div class="logo">
        {!! HTML::image($manufacturer->getImage(), '') !!}
        <input type="file" name="m_{{$manufacturer->id}}[logo]" class='logo-input'>
        <input type="hidden" name="m_{{$manufacturer->id}}[img_url]" value='{!! $manufacturer->img_url !!}'>
    </div>
    
    <div class="desc">
        <textarea name="m_{{$manufacturer->id}}[description]" class="form-control" rows="5">{!! $manufacturer->description !!}</textarea>
    </div>

    <div class="input-group read-more">
        <span class="input-group-addon">Read More</span>
        <input type="text" name='m_{{$manufacturer->id}}[web_url]' class="form-control" 
                placeholder="Read More Link" value="{!! $manufacturer->web_url !!}">
    </div>

</div>