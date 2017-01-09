<?php

namespace Backend\Api\ApiInterfaces\SolutionApi;

interface SolutionApiInterface
{
    /**
     * @param array|null $query
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listSolutions($query = null);

    /**
     * @param int $solution_id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getSolution(int $solution_id);

    /**
     * @param int $solution_id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function changeToNormalSolution(int $solution_id);

    /**
     * @param int $solution_id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function changeToProgram(int $solution_id);

    /**
     * @param int $solution_id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function changeToPendingToNormalSolution(int $solution_id);

    /**
     * @param int $solution_id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function changeToPendingToProgram(int $solution_id);

    /**
     * @param int $solution_id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function approve(int $solution_id);

    /**
     * @param int $solution_id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function reject(int $solution_id);

    /**
     * @param int $solution_id
     * @param array $data
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function modifySolution(int $solution_id, array $data);
}
