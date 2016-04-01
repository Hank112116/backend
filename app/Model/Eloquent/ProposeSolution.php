<?php

namespace Backend\Model\Eloquent;

use Illuminate\Database\Eloquent\Model as Eloquent;

class ProposeSolution extends Eloquent
{

    protected $table = 'log_propose_solution';
    protected $primaryKey = 'log_id';

    public function user()
    {
        return $this->belongsTo(User::class, 'proposer_id', 'user_id');
    }

    public function solution()
    {
        return $this->belongsTo(Solution::class, 'solution_id', 'solution_id');
    }
}
