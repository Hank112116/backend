<?php

namespace Backend\Api\ApiInterfaces\ProjectApi;

use Backend\Model\Eloquent\Project;

interface ProjectApiInterface
{
    /**
     * @param array|null $query
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listProjects($query = null);

    /**
     * @param Project $project
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function releaseSchedule(Project $project);

    /**
     * @param Project $project
     * @param array   $experts
     * @param int     $admin_id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function staffRecommendExperts(Project $project, array $experts, int $admin_id);

    /**
     * @param Project $project
     * @param array   $pms
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function assignPM(Project $project, array $pms);

    /**
     * @param Project $project
     * @param array   $data
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function updateMemo(Project $project, array $data);
}
