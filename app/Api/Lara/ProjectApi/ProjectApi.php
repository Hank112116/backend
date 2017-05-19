<?php

namespace Backend\Api\Lara\ProjectApi;

use Backend\Api\ApiInterfaces\ProjectApi\ProjectApiInterface;
use Backend\Api\Lara\BasicApi;
use Backend\Enums\URI\API\HWTrek\ProjectApiEnum;
use Backend\Model\Eloquent\Project;

class ProjectApi extends BasicApi implements ProjectApiInterface
{
    /**
     * {@inheritDoc}
     */
    public function listProjects($query = null)
    {
        $url = $this->hwtrek_url . ProjectApiEnum::PROJECTS;

        if (!is_null($query)) {
            $url = $url . '?' . http_build_query($query, null, '&', PHP_QUERY_RFC3986);
        }

        return $this->get($url);
    }

    /**
     * {@inheritDoc}
     */
    public function releaseSchedule(Project $project)
    {
        $uri = str_replace('(:any)', $project->uuid, ProjectApiEnum::RELEASE);
        $url = $this->hwtrek_url . $uri;

        return $this->patch($url);
    }

    /**
     * {@inheritDoc}
     */
    public function staffRecommendExperts(Project $project, array $experts, int $admin_id)
    {
        $data = [
            'json' => [
                'adminId'  => $admin_id,
                'experts'  => $experts
            ]
        ];

        $uri = str_replace('(:any)', $project->uuid, ProjectApiEnum::STAFF_RECOMMEND_EXPERTS);
        $url = $this->hwtrek_url . $uri;

        return $this->post($url, $data);
    }

    /**
     * {@inheritDoc}
     */
    public function assignPM(Project $project, array $pms)
    {
        $data = [
            'json' => [
                'pms'  => $pms
            ]
        ];

        $uri = str_replace('(:any)', $project->uuid, ProjectApiEnum::ASSIGN_PMS);
        $url = $this->hwtrek_url . $uri;

        return $this->put($url, $data);
    }

    /**
     * {@inheritDoc}
     */
    public function updateMemo(Project $project, array $request)
    {
        $update_data = [];

        // Mapping request
        foreach ($request as $key => $item) {
            switch ($key) {
                case 'description':
                    $update_data['description'] = $item;
                    break;
                case 'report_action':
                    $update_data['reportAction'] = $item;
                    break;
                case 'schedule_note':
                    $update_data['scheduleNote'] = $item;
                    break;
                case 'schedule_note_grade':
                    $update_data['scheduleNoteGrade'] = $item;
                    break;
                case 'tags':
                    $tmp = explode(',', $item);
                    $update_data['tags'] = $tmp;
                    break;
            }
        }

        $data = [
            'json' => $update_data
        ];

        $uri = str_replace('(:any)', $project->uuid, ProjectApiEnum::MEMO);
        $url = $this->hwtrek_url . $uri;

        return $this->patch($url, $data);
    }
}
