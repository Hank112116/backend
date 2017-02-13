<?php namespace Backend\Model\Eloquent;

use Illuminate\Database\Eloquent\Model as Eloquent;

class ProjectMailExpert extends Eloquent
{

    //
    protected $table = 'project_expert';
    public $timestamps = false;
    public static $unguarded = true;

    public function adminer()
    {
        return $this->hasOne(Adminer::class, 'id', 'admin_id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'user_id', 'expert_id');
    }

    public function project()
    {
        return $this->hasOne(Project::class, 'project_id', 'project_id');
    }

    /**
     * @return User
     */
    public function projectOwner()
    {
        return $this->project->user;
    }

    public function dateSend()
    {
        return $this->date_send;
    }
}
