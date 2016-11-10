<?php namespace Backend\Http\Controllers;

use Backend\Api\ApiInterfaces\ProjectApi\ReleaseApiInterface;
use Backend\Repo\RepoInterfaces\UserInterface;
use Backend\Repo\RepoInterfaces\AdminerInterface;
use Backend\Repo\RepoInterfaces\ProjectInterface;
use Backend\Repo\RepoInterfaces\ProjectMailExpertInterface;
use Backend\Repo\RepoInterfaces\GroupMemberApplicantInterface;
use Backend\Facades\Log;
use Backend\Model\Plain\TagNode;
use Mews\Purifier\Purifier;
use Carbon;

class EmailSendController extends BaseController
{
    private $user_repo;
    private $adminer_repo;
    private $project_repo;
    private $pme_repo;
    private $applicant_repo;
    private $purifier;

    public function __construct(
        UserInterface $user,
        AdminerInterface $adminer,
        ProjectInterface $project,
        ProjectMailExpertInterface $projectMailExpert,
        GroupMemberApplicantInterface $applicant,
        Purifier $purifier
    ) {
        parent::__construct();
        $this->user_repo      = $user;
        $this->adminer_repo   = $adminer;
        $this->project_repo   = $project;
        $this->pme_repo       = $projectMailExpert;
        $this->applicant_repo = $applicant;
        $this->purifier       = $purifier;
    }
    public function hubMailSend()
    {
        if (empty($this->request->session()->get('admin'))) {
            $res   = ['status' => 'fail', 'msg' => 'Permissions denied'];
            return response()->json($res);
        }

        //PM user_id
        $frontPM = [];
        $backPM  = [];
        foreach ($this->adminer_repo->findFrontManager()->toArray() as $row) {
            $frontPM[] = $row['hwtrek_member'];
        }
        foreach ($this->adminer_repo->findBackManager()->toArray() as $row) {
            $backPM[] = $row['hwtrek_member'];
        }
        $input   = $this->request->all();
        $expert1 = $this->user_repo->findExpert($input['expert1']);
        $expert2 = $this->user_repo->findExpert($input['expert2']);
        if (sizeof($expert1) <= 0 || sizeof($expert2) <= 0) {
            $res   = ['status' => 'fail', 'msg' => 'Error expert id!'];
            return response()->json($res);
        }

        $project = $this->project_repo->find($input['projectId']);

        $log_action = 'Send approved mail';
        $log_data   = [
            'project' => $project->project_id,
        ];
        Log::info($log_action, $log_data);

        //save project_expert table
        $date = Carbon::now();
        $experts[] = $input['expert1'];
        $experts[] = $input['expert2'];
        foreach ($experts as $expert) {
            $data['expert_id']  = $expert;
            $data['project_id'] = $input['projectId'];
            $data['admin_id']   = $this->request->session()->get('admin');
            $data['date_send']  = $date;
            $this->applicant_repo->insertItem($data);
            $this->pme_repo->insertItem($data);
            
        }
        /* @var ReleaseApiInterface $release_api*/
        $release_api = app()->make(ReleaseApiInterface::class, ['project' => $project]);

        $response    = $release_api->staffRecommendExperts();

        if ($response->getStatusCode() != \Illuminate\Http\Response::HTTP_NO_CONTENT) {
            $res   = ['status' => 'fail', 'msg' => $response->getContent()];
            return response()->json($res);
        }
        
        $view = view()->make('project.row')->with(['project' => $project, 'tag_tree' => TagNode::tags()])->render();
        $res   = ['status' => 'success', 'view'=> $view];

        return response()->json($res);
    }
}
