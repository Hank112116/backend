<?php namespace Backend\Model\ModelInterfaces;

interface ProjectTagBuilderInterface
{
    public function projectTagTree();
    public function projectTagOutput(array $project_tag_ids);
}
