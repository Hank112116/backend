<?php namespace Backend\Model;

use Backend\Model\Plain\TagNode;
use Backend\Model\Eloquent\ProjectTag;
use Backend\Model\Eloquent\Tag as OtherTag;
use Backend\Model\Plain\TagTree;
use Backend\Model\ModelInterfaces\TagBuilderInterface;
use Backend\Enums\TechCategory;
use Backend\Enums\TechTag;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class TagBuilder implements TagBuilderInterface
{
    private $project_tag;
    private $other_tag;

    public function __construct(ProjectTag $project_tag, OtherTag $other_tag)
    {
        $this->project_tag = $project_tag;
        $this->other_tag   = $other_tag;
    }

    public function tagOutput(array $project_tag_ids = [])
    {

        if (!$project_tag_ids) {
            return '';
        }
        $tags = $this->tagsMapping($project_tag_ids);
        return implode(',', $tags);
    }

    public function tagTree()
    {
        $tree = new TagTree();
        $this->buildTreeParents($tree);
        $this->buildTreeNodes($tree);
        return $tree;
    }

    private function buildTreeParents(TagTree $tree)
    {
        $tags = Collection::make(TechCategory::TECH_CATEGORIES);
        $tags->map(
            function ($tag) use ($tree) {
                $tree->createParent($tag);
            }
        );
    }

    private function buildTreeNodes(TagTree $tree)
    {
        $tags = Collection::make(TechTag::TECH_TAGS);
        $tags->map(
            function ($tags, $parent_tag) use ($tree) {
                $other_tags = $this->other_tag->where('classified_slug', 'LIKE', "{$parent_tag}:%")->get();
                if ($other_tags->count() > 0) {
                    foreach ($other_tags as $other_tag) {
                        array_push($tags, $other_tag->classified_slug);
                    }
                }

                $tree->createNode($tags, $parent_tag);
            }
        );
    }

    public function tagsMapping($project_tag_ids)
    {
        $result       = [];
        $mapping_tags = TagNode::tags();
        foreach ($project_tag_ids as $tag) {
            if (array_key_exists($tag, $mapping_tags)) {
                $result[] = $mapping_tags[$tag];
            }
        }

        return $result;
    }

    public function tagTransformKey($tag)
    {
        $separator = '-';

        // Try normal slug first
        $slug = Str::slug($tag, $separator);

        // If get an empty string after slug. Try another encode way
        // Following code borrow from Illuminate\Support\Str::slug() without convert to ascii step
        if (empty($slug)) {
            // Convert all dashes/underscores into separator
            $flip = $separator == '-' ? '_' : '-';

            $title = preg_replace('!['.preg_quote($flip).']+!u', $separator, $tag);

            // Remove all characters that are not the separator, letters, numbers, or whitespace.
            $title = preg_replace('![^'.preg_quote($separator).'\pL\pN\s]+!u', '', mb_strtolower($title));

            // Replace all separator characters and whitespace by a single separator
            $title = preg_replace('!['.preg_quote($separator).'\s]+!u', $separator, $title);

            $slug = mb_strtolower(rawurlencode(trim($title, $separator)));
        }

        $result = $slug;

        return rtrim($result, '%');
    }
}
