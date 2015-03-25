<?php namespace Backend\Model\Plain;

use Backend\Model\Eloquent\ProjectTag;

class ProjectTagTree
{
    private $parent_id = 0;
    public $parents;

    public function createParent(ProjectTag $parent_tag)
    {
        $this->parents[$parent_tag->tag_id] = new ProjectTagParent($parent_tag);
    }

    public function createNode(ProjectTag $tag)
    {
        if (!array_key_exists($tag->parent_id, $this->parents)) {
            return;
        }

        $this->parents[$tag->parent_id]->appendNode($tag);
    }

    public function isParent(ProjectTag $tag)
    {
        return ($tag->parent_id == $this->parent_id);
    }
}
