<?php

namespace Backend\Model\Eloquent;

use Illuminate\Database\Eloquent\Model as Eloquent;

class ProposeSolution extends Eloquent
{

    const EVENT_SUGGEST = 'suggest';
    const EVENT_PROPOSE = 'propose';
    const EVENT_CLICK   = 'click';
    
    protected $table = 'log_propose_solution';
    protected $primaryKey = 'log_id';

    public function user()
    {
        return $this->belongsTo(User::class, 'proposer_id', 'user_id')
            ->select([
                'user_id', 'user_name', 'last_name', 'user_type',
                'is_sign_up_as_expert', 'is_apply_to_be_expert',
                'company', 'active', 'company_url', 'suspended_at',
                'user_role', 'email_verify'
            ]);
    }

    public function solution()
    {
        return $this->belongsTo(Solution::class, 'solution_id', 'solution_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id', 'project_id')
            ->select(['project_id', 'uuid', 'user_id', 'project_title']);
    }

    public function isPMPropose()
    {
        return $this->event ===  self::EVENT_SUGGEST;
    }

    public function isUserPropose()
    {
        return $this->event ===  self::EVENT_PROPOSE;
    }

    /**
     * @return User
     */
    public function projectOwner()
    {
        return $this->project->user;
    }

    /**
     * @return User
     */
    public function solutionOwner()
    {
        return $this->solution->user;
    }

    public function solutionId()
    {
        return $this->solution_id;
    }

    public function projectId()
    {
        return $this->project_id;
    }

    public function proposeTime()
    {
        return $this->propose_time;
    }
}
