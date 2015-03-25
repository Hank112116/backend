<?php namespace Backend\Model\ModelTrait;

trait ProjectTagTrait
{
    private $project_tags;

    public function getProjectTagsAttribute()
    {
        if (!isset($this->project_tags)) {
            $this->project_tags = !$this->tags ?
                [] : explode(',', $this->tags);
        }

        return $this->project_tags;
    }

    public function hasProjectTag($tag_id)
    {
        return in_array($tag_id, $this->getProjectTagsAttribute());
    }
}
