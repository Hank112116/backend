<?php namespace Backend\Model;

use Backend\Model\Plain\TagNode;
use Backend\Model\Eloquent\ProjectTag;
use Backend\Model\Plain\TagTree;
use Backend\Model\ModelInterfaces\TagBuilderInterface;
use Illuminate\Support\Collection;
use Backend\Enums\TechCategory;
use Backend\Enums\TechTag;

class TagBuilder implements TagBuilderInterface
{
    private $tags;
    private $tags_mapper;

    public function __construct(ProjectTag $project_tag)
    {
        $this->project_tag = $project_tag;
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
            function ($tag, $parent_tag) use ($tree) {
                $tree->createNode($tag, $parent_tag);
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
}
