<?php

namespace Backend\Model\Eloquent;

use Illuminate\Database\Eloquent\Builder;

class DuplicateSolution extends Solution
{

    protected $table = 'editing_solution';
    protected $primaryKey = 'solution_id';

    public $wait_approve_status = [
        'solution_draft' => '1',
    ];

    /**
     * Query  Wait-Approve
     * @param Builder $query
     * @return mixed
     */
    public function scopeQueryWaitApproved(Builder $query)
    {
        return $query->where($this->wait_approve_status);
    }
}
