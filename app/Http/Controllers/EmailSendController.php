<?php namespace Backend\Http\Controllers;

use Backend\Http\Controllers\Controller;
use Backend\Repo\RepoInterfaces\UserInterface;
use Backend\Repo\RepoInterfaces\AdminerInterface;
use Backend\Repo\RepoInterfaces\MailTemplateInterface;
use Backend\Repo\RepoInterfaces\ProjectInterface;
use Mews\Purifier\Purifier;
use Backend\Model\Eloquent\ProjectMailExpert;
use Config;
use Input;
use PHPMailer;
use EmailSend;
use Response;
use Session;
use Carbon;

class EmailSendController extends BaseController
{
    public function __construct(
        UserInterface $user,
        AdminerInterface $adminer,
        MailTemplateInterface $mt,
        ProjectInterface $project,
        Purifier $purifier
    ) {
        parent::__construct();
        $this->user_repo    = $user;
        $this->adminer_repo = $adminer;
        $this->project_repo = $project;
        $this->mail_repo    = $mt;
        $this->purifier     = $purifier;
    }
    public function hubMailSend()
    {
        //PM user_id
        $frontPM = [12,13,14,16];
        $backPM  = [2,3,7,8];
        $input   = Input::all();
        $expert1 = $this->user_repo->findExpert($input["expert1"]);
        $expert2 = $this->user_repo->findExpert($input["expert2"]);
        $user    = $this->user_repo->find($input["userId"]);
        $project = $this->project_repo->find($input["projectId"]);
        $emailr  = new EmailSend;
        //find pm
        $projectPM = $input["PM"];
        $contentData["frontPM_fname"] = "WhoKnow";
        if ($projectPM) {
            $tmpArr = explode(",", $projectPM);
            foreach ($tmpArr as $row) {
                if (in_array($row, $frontPM)) {
                    $adminer = $this->adminer_repo->find($row);
                    $nameTmp = explode(" ", $adminer->name);
                    $contentData["frontPM_fname"] = $nameTmp[0];
                    $emailData["cc"] = $adminer->email;
                }
                if (in_array($row, $backPM)) {
                    $adminer = $this->adminer_repo->find($row);
                    $emailData["bcc"] = Config::get('email.bcc');
                    array_push($emailData["bcc"], $adminer->email);
                }
            }
        }
        //set mail content
        $contentData["project_24char_title"] = $input["projectTitle"];
        $contentData["owner_fname"] = $user->user_name;
        $contentData["expert1_name"] = $expert1[0]->textFullName();
        $contentData["expert2_name"] = $expert2[0]->textFullName();
        $contentData["expert1_img"] = $expert1[0]->getImagePath();
        $contentData["expert2_img"] = $expert2[0]->getImagePath();
        $contentData["expert1_link"] = $expert1[0]->textFrontLink();
        $contentData["expert2_link"] = $expert2[0]->textFrontLink();
        $contentData["expert1_corp"] = $expert1[0]->company;
        $contentData["expert2_corp"] = $expert2[0]->company;
        $contentData["expert1_location"] = $expert1[0]->city.",".$expert1[0]->country;
        $contentData["expert2_location"] = $expert2[0]->city.",".$expert2[0]->country;
        $contentData["expert1_business"] = $expert1[0]->business_id;
        $contentData["expert2_business"] = $expert2[0]->business_id;
        $expert1Tags = $expert1[0]->getIndustryArray();
        $expert2Tags = $expert2[0]->getIndustryArray();
        if (isset($expert1Tags[0])) {
            $contentData["expert1_tag1"] = $expert1Tags[0];
        } else {
            $contentData["expert1_tag1"] = "";
        }
        if (isset($expert2Tags[0])) {
            $contentData["expert2_tag1"] = $expert2Tags[0];
        } else {
            $contentData["expert2_tag1"] = "";
        }
        $template = $this->findTemplate(EmailSend::HUB_SCHEDULE_RELEASE);
        $basicTemplate = view('email_template.hwtrek-inline');
        $title = $emailr->content_replace($template->subject, $contentData);
        $contentData["content"] = $emailr->content_replace($template->message, $contentData);
        $body = $emailr->content_replace($basicTemplate, $contentData);
        $emailData["address"] = $user->email;
        $emailData["title"] = $title;
        $emailData["body"] = $body;
        $status = $emailr->send($emailData);

        //save project_expert table
        if ($status) {
            $date = Carbon::now();
            $experts[] = $input["expert1"];
            $experts[] = $input["expert2"];
            foreach ($experts as $expert) {
                $projectMailExpertModel = new ProjectMailExpert();
                $projectMailExpertModel->project_id = $input["projectId"];
                $projectMailExpertModel->expert_id = $expert;
                $projectMailExpertModel->admin_id = Session::get("admin");
                $projectMailExpertModel->date_send = $date;
                $projectMailExpertModel->save();

            }
            $res   = ['status' => 'success'];
        } else {
            $res   = ['status' => 'fail', "msg" => "Email send fail."];
        }
        return Response::json($res);
    }

    private function findTemplate($templateId)
    {
        $template = $this->mail_repo->find($templateId);
        return $template;
    }
}
