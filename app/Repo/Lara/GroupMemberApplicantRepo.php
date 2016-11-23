<?php namespace Backend\Repo\Lara;

use Backend\Repo\RepoInterfaces\GroupMemberApplicantInterface;
use Backend\Model\Eloquent\GroupMemberApplicant;
use Backend\Model\Eloquent\ProjectGroup;
use Backend\Model\Eloquent\Adminer;
use Backend\Model\Eloquent\ProjectMember;

class GroupMemberApplicantRepo implements GroupMemberApplicantInterface
{
    private $applicant;
    private $adminer;
    private $project_group;
    private $project_member;

    public function __construct(
        GroupMemberApplicant $applicant,
        ProjectGroup $project_group,
        Adminer $adminer,
        ProjectMember $project_member
    ) {
        $this->applicant      = $applicant;
        $this->adminer        = $adminer;
        $this->project_group  = $project_group;
        $this->project_member = $project_member;
    }
    public function insertItem($data)
    {
        $project_group   = $this->project_group->where('project_id', $data['project_id'])->first();

        $adminer         = $this->adminer->find($data["admin_id"]);

        $project_member  = $this->project_member->where('project_id', $data['project_id'])
                                                ->where('user_id', $data['expert_id'])->first();

        $applicant_model = new GroupMemberApplicant();

        if (is_null($project_member)) {
            $applicant_model->is_expired = 0;
        } else {
            $applicant_model->is_expired = 1;
        }

        $privileges['1'] = 'project';
        $privileges['2'] = $data['project_id'];
        $privileges['3'] = '2';

        $applicant_model->group_id              = $project_group->group_id;
        $applicant_model->user_id               = $data['expert_id'];
        $applicant_model->is_referral_expert    = 1;
        $applicant_model->referral              = $adminer->hwtrek_member;
        $applicant_model->apply_date            = $data['date_send'];
        $applicant_model->additional_privileges = json_encode([$privileges]);
        $applicant_model->event                 = GroupMemberApplicant::REFERRAL_PM;
        $applicant_model->save();
    }
}
