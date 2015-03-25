<?php

namespace Backend\Model\Eloquent;

class DuplicateProject extends Project
{

    protected $table = 'editing_project';
    protected $primaryKey = 'project_id';

    public function perks()
    {
        return $this->hasMany(DuplicatePerk::class, 'project_id', 'project_id');
    }

    public function isOnGoing()
    {
        return true; //Duplicate project must on-going
    }
}
