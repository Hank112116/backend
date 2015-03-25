<?php namespace Backend\Model;

use Backend\Model\Plain\ProjectTagNode;
use Backend\Model\Eloquent\ProjectTag;
use Backend\Model\Plain\ProjectTagTree;
use Backend\Model\ModelInterfaces\ProjectTagBuilderInterface;
use Illuminate\Support\Collection;

class ProjectTagBuilder implements ProjectTagBuilderInterface
{
    private $tags;
    private $tags_mapper;

    public function __construct(ProjectTag $project_tag)
    {
        $this->project_tag = $project_tag;
    }

    public function projectTagOutput(array $project_tag_ids = [])
    {
        if (!$project_tag_ids) {
            return '';
        }

        $tags = [];
        $mapping = $this->tagsMapping();
        foreach ($project_tag_ids as $tag_id) {
            if (array_key_exists($tag_id, $mapping)) {
                $tags[] = $mapping[$tag_id];
            }
        }

        return implode(',', $tags);
    }

    public function projectTagTree()
    {
        $tags = $this->tags();
        $tree = new ProjectTagTree();
        $this->buildTreeParents($tree, $tags);
        $this->buildTreeNodes($tree, $tags);

        return $tree;
    }

    private function buildTreeParents(ProjectTagTree $tree, Collection $tags)
    {
        $tags->map(
            function ($tag) use ($tree) {
                if ($tree->isParent($tag)) {
                    $tree->createParent($tag);
                }
            }
        );
    }

    private function buildTreeNodes(ProjectTagTree $tree, Collection $tags)
    {
        $tags->map(
            function ($tag) use ($tree) {
                $tree->createNode($tag);
            }
        );
    }

    private function tags()
    {
        if (!isset($this->tags)) {
            $this->tags = $this->project_tag->all();
        }

        return $this->tags;
    }

    private function tagsMapping()
    {
        if (!isset($this->tags_mapper)) {
            $this->setTagsMapper();
        }

        return $this->tags_mapper;
    }

    private function setTagsMapper()
    {
        $tree = new ProjectTagTree();
        $this->tags_mapper = [];
        $slug_name_mapper = ProjectTagNode::tags();

        foreach ($this->tags() as $tag) {
            if (!$tree->isParent($tag)) {
                $this->tags_mapper[$tag->tag_id] = $slug_name_mapper[$tag->slug];
            }
        }
    }
}
