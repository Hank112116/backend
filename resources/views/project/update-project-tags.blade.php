@foreach ($project_tag_tree->parents as $parent)
<div class="project-tag-tree-wrapper">
    <h3 class='project-tag-tree-title'>{!!  $parent->name !!}</h3>
    <div class='project-tags-wrapper select-tags'>
        @foreach ($parent->nodes as $tag)
        <div class='btn btn-primary tag {!!  $project->hasProjectTag($tag->key)? 'active' : ''  !!}'
             data-id='{!! $tag->key !!}'>
             {!!  $tag->name !!}
        </div>
        @endforeach
    </div>
</div>
@endforeach
<input type="hidden" name="tags" value="{{ implode(',', $project->getProjectTagsAttribute()) }}" />