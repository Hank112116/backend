<div class="row">
	<div class="col-md-10 col-md-offset-1">

		<div class="panel panel-default">
		  	<div class="panel-heading">Feature Tags</div>
		  	<div class="panel-body">
                @foreach ($project_tag_tree->parents as $parent)
                    @if($parent->containsActiveProjectTag($project->project_tags))
                        <div class='expertise-category'>
                            <p class='category_title'>{!! $parent->name !!}</p>

                            @foreach ($parent->nodes as $tag)
                                @if($project->hasProjectTag($tag->key))
                                <span class='tag'>{!! $tag->name !!}</span>
                                @endif
                            @endforeach
                        </div>
                    @endif
                @endforeach

                @if ($project->getInternalTags())
                        <div class='expertise-category'>
                            <p class='category_title'>Internal Tags</p>
                            @foreach ($project->getInternalTags() as $tag)
                                <span class='tag'>{{ $tag }}</span>
                            @endforeach
                        </div>
                @endif
		  	</div>
		</div>

	</div>
</div>
