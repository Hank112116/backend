<?php namespace Backend\Model\ModelInterfaces;

interface TagBuilderInterface
{
    public function tagTree();
    public function tagOutput(array $project_tag_ids);
    public function tagsMapping($project_tag_ids);
}
