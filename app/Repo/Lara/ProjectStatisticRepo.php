<?php namespace Backend\Repo\Lara;

use Backend\Api\ApiInterfaces\ProjectApi\StatisticApiInterface;
use Backend\Model\Eloquent\Project;
use Backend\Repo\RepoInterfaces\ProjectStatisticInterface;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use App;

class ProjectStatisticRepo implements ProjectStatisticInterface
{
    public function loadProjectStatistic(Project $project)
    {
        $projects = [];
        array_push($projects, $project);
        $projects = Collection::make($projects);

        return $this->loadProjectStatistics($projects);
    }

    public function loadProjectStatistics(Collection $projects)
    {
        /* @var StatisticApiInterface $project_statistic_api*/
        $project_statistic_api = App::make(StatisticApiInterface::class, ['projects' => $projects]);

        $response = $project_statistic_api->load();

        if ($response->getStatusCode() !== Response::HTTP_OK) {
            return [];
        }

        $statistics = json_decode($response->getContent());
        
        if (empty($statistics)) {
            return [];
        }
        
        $project_statistics = [];
        foreach ($statistics as $statistic) {
            $data['id']                 = $statistic->id;
            $data['page_view']          = $statistic->pageView;
            $data['staff_referral']     = $statistic->staffReferral;
            $data['user_referral']      = $statistic->userReferral;
            $data['last_referral_time'] = $statistic->lastReferralTime;
            $data['staff_proposed']     = $statistic->staffProposed;
            $data['user_proposed']      = $statistic->userProposed;
            $data['last_proposed_time'] = $statistic->lastProposedTime;
            $project_statistics[$statistic->id] = (object) $data;
        }
        return $project_statistics;
    }
}
