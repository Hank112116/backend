<?php namespace Backend\Model\ModelTrait;

use Backend\Model\Plain\TagNode;

trait TagTrait
{
    private $project_tags;

    public function getProjectTagsAttribute($amount = 0)
    {
        if (isset($this->tags)) {
            $this->project_tags = json_decode($this->tags, true);
            if (!is_array($this->project_tags)) {
                return [];
            }

        } else {
            $this->project_tags = [];
        }
        if ($amount === 0) {
            return $this->project_tags;
        } else {
            return array_slice($this->project_tags, 0, $amount);
        }
    }

    public function hasProjectTag($tag)
    {
        return in_array($tag, $this->getProjectTagsAttribute());
    }

    public function isSimilarTag($search_tag)
    {
        $tags = $this->getProjectTagsAttribute();
        foreach ($tags as $tag) {
            if (stristr($tag, $search_tag)) {
                return true;
            }
        }
        return false;
    }

    public function getMappingTag($tag_tree = [], $amount = 0)
    {
        $tags = $this->getProjectTagsAttribute();
        if (empty($tag_tree)) {
            $tag_tree = TagNode::tags();
        }
        if (empty($tags)) {
            return [];
        }
        $mapping   = [];
        foreach ($tags as $tag) {
            if (array_key_exists($tag, $tag_tree)) {
                $mapping[] = $tag_tree[$tag];
            }
        }
        if ($amount === 0) {
            return $mapping;
        } else {
            return array_slice($mapping, 0, $amount);
        }
    }
}
