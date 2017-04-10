<?php

namespace Backend\Api\ApiInterfaces\MarketingApi;

interface LowPriorityObjectApiInterface
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function loadUsers();

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function loadProjects();

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function loadSolutions();

    /**
     * @param int $user_id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addUser(int $user_id);

    /**
     * @param int $project_id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addProject(int $project_id);

    /**
     * @param int $solution_id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addSolution(int $solution_id);

    /**
     * @param int $user_id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function revokeUser(int $user_id);

    /**
     * @param int $project_id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function revokeProject(int $project_id);

    /**
     * @param int $solution_id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function revokeSolution(int $solution_id);
}
