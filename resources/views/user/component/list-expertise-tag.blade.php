@if ($user->expertises)
{{ implode(' / ', $user->getMappingTag(2)) }} <br/>
@endif
@if ($memo)
<span class="table--text-light"> {{ str_replace(',', ' / ', $memo->tags) }} </span><br/>
<a href="javascript:void(0)" class="btn-mini internal-tag" rel="{!! $user->user_id !!}" tags="{{ $memo->tags }}" expertise-tags="{{ implode(' / ', $user->getMappingTag()) }}">Add</a>
@else
<a href="javascript:void(0)" class="btn-mini internal-tag" rel="{!! $user->user_id !!}" tags="" expertise-tags="{{ implode(' / ', $user->getMappingTag()) }}">Add</a>
@endif