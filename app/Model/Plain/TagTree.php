<?php namespace Backend\Model\Plain;

use Backend\Model\Eloquent\Tag;

class TagTree
{
    public $parents;

    public function createParent($parent_tag)
    {
        $this->parents[$parent_tag] = new TagParent($parent_tag);
    }

    public function createNode($tags, $parent_tag, $all_tags)
    {
        if (!array_key_exists($parent_tag, $this->parents)) {
            return;
        }

        foreach ($tags as $tag) {
            $this->parents[$parent_tag]->appendNode($tag, $all_tags);
        }
    }
}
