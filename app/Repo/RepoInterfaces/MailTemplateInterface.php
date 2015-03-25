<?php namespace Backend\Repo\RepoInterfaces;

interface MailTemplateInterface
{
    public function byActive($active = true);
    public function find($id);
    public function getTags();
    public function create($data);
    public function update($id, $data);
    public function switchActive($id);
}
