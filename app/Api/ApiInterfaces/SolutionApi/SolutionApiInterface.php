<?php

namespace Backend\Api\ApiInterfaces\SolutionApi;

use Symfony\Component\HttpFoundation\File\UploadedFile;

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

    /**
     * @param int $solution_id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function onShelf(int $solution_id);

    /**
     * @param int $solution_id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function offShelf(int $solution_id);

    /**
     * @param int $solution_id
     * @param UploadedFile $picture
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function uploadPicture(int $solution_id, UploadedFile $picture);
}
