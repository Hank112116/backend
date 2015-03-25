<?php namespace Backend\Repo\RepoInterfaces;

interface ExpertiseInterface
{
    public function getTags();
    public function getDisplayTags(array $tag_ids);
}
