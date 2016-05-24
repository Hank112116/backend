{{ implode(' / ', $user->getMappingTag($tag_tree, 2)) }} <br/>
@if ($user->internalUserMemo)
<span class="table--text-light"> {{ str_replace(',', ' / ', $user->internalUserMemo->tags) }} </span><br/>
<a href="javascript:void(0)" class="btn-mini internal-tag" rel="{!! $user->user_id !!}" tags="{{ $user->internalUserMemo->tags }}" expertise-tags="{{ implode(' / ', $user->getMappingTag($tag_tree)) }}">Add</a>
@else
<a href="javascript:void(0)" class="btn-mini internal-tag" rel="{!! $user->user_id !!}" tags="" expertise-tags="{{ implode(' / ', $user->getMappingTag($tag_tree)) }}">Add</a>
@endif