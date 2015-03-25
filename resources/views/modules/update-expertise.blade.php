@foreach ($expertise_tags as $parent_tag)
<div class="project-tag-tree-wrapper">
    <h3 class='project-tag-tree-title'>{!!  $parent_tag['short_name'] !!}</h3>

    <div class='project-tags-wrapper select-tags'>
        @foreach ($parent_tag['children'] as $tag)
        <div class='btn btn-primary tag {!!  $user->hasExpertiseTag($tag['expertise_id'])? 'active' : ''  !!}'
             data-id='{!! $tag['expertise_id'] !!}'>
             {!! $tag['short_name'] !!}
        </div>
        @endforeach
    </div>
</div>
@endforeach

<input type="hidden" name="expertises" value="{!! $user->expertises !!}"/>
