<?php namespace Backend\Model\ModelTrait;

use Backend\Model\Plain\TagNode;

trait TagTrait
{
    private $project_tags;

    public function getProjectTagsAttribute()
    {
        if (isset($this->tags)) {
            $this->project_tags = json_decode($this->tags, true);
        } else {
            $this->project_tags = [];
        }

        return $this->project_tags;
    }

    public function hasProjectTag($tag)
    {
        return in_array($tag, $this->getProjectTagsAttribute());
    }

    public function getMappingTag($amount = 0)
    {
        $tags      = $this->getProjectTagsAttribute();
        $node_tags = TagNode::tags();
        $mapping   = [];
        foreach ($tags as $tag) {
            if (array_key_exists($tag, $node_tags)) {
                $mapping[] = $node_tags[$tag];
            }
        }
        if ($amount === 0) {
            return $mapping;
        } else {
            return array_slice($mapping, 0, $amount);
        }
    }
}
