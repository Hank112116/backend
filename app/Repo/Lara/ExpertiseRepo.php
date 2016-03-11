<?php namespace Backend\Repo\Lara;

use Backend\Repo\RepoInterfaces\ExpertiseInterface;
use Backend\Model\Eloquent\Expertise;

class ExpertiseRepo implements ExpertiseInterface
{
    private $expertise;

    public function __construct(Expertise $expertise)
    {
        $this->expertise = $expertise;
    }

    public function getDisplayTags(array $tag_ids)
    {
        return $this->expertise->getExpertiseTagsArray($tag_ids);
    }

    public function getTags()
    {
        return $this->buildTree($this->expertise->all()->toArray());
    }

    private function buildTree(array $elements, $parentId = 0)
    {
        $branch = [];

        foreach ($elements as $element) {
            if ($element['parent_id'] == $parentId) {
                if ($children = $this->buildTree($elements, $element['expertise_id'])) {
                    $element['children'] = $children;
                }

                $branch[] = $element;
            }
        }

        return $branch;
    }
}
