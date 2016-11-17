<?php

namespace Backend\Http\Controllers;

use Backend\Repo\RepoInterfaces\CommentInterface;
use Backend\Api\ApiInterfaces\CommentApiInterface;
use Backend\Facades\Log;

class CommentController extends BaseController
{
    private $comment_repo;
    private $comment_api;

    public function __construct(
        CommentInterface $comment_repo,
        CommentApiInterface $comment_api
    ) {
        parent::__construct();
        $this->comment_repo = $comment_repo;
        $this->comment_api  = $comment_api;
    }

    public function showProfession()
    {
        return view('comment.comments')
            ->with([
                'title' => 'Expert',
                'type'  => 'user'
            ]);
    }

    public function professions()
    {
        return response()->json(
            $this->comment_repo->professionTopicsByPage($this->page, $this->per_page)
        );
    }

    public function showProject()
    {
        return view('comment.comments')
            ->with([
                'title' => 'Project',
                'type'  => 'project'
            ]);
    }

    public function projects()
    {
        return response()->json(
            $this->comment_repo->projectTopicsByPage($this->page, $this->per_page)
        );
    }


    public function showSolution()
    {
        return view('comment.comments')
            ->with([
                'title' => 'Solution',
                'type'  => 'solution'
            ]);
    }

    public function solutions()
    {
        return response()->json(
            $this->comment_repo->solutionTopicsByPage($this->page, $this->per_page)
        );
    }

    public function searchProfessions()
    {
        $where  = $this->request->get('where', '');
        $search = $this->request->get('search', '');

        return response()->json([
            'data' => $this->comment_repo->professionTopicsBySearch($where, $search)
        ]);
    }

    public function searchProjects()
    {
        $where  = $this->request->get('where', '');
        $search = $this->request->get('search', '');

        return response()->json([
            'data' => $this->comment_repo->projectTopicsBySearch($where, $search)
        ]);
    }

    public function searchSolutions()
    {
        $where  = $this->request->get('where', '');
        $search = $this->request->get('search', '');

        return response()->json([
            'data' => $this->comment_repo->solutionTopicsBySearch($where, $search)
        ]);
    }

    /*
     * @param int $comment_id
     * @return json $data
     */
    public function delete($comment_id)
    {
        $log_action = 'Delete comments';
        $log_data   = [
            'comment_id' => $comment_id
        ];
        Log::info($log_action, $log_data);

        return $this->comment_api->delete($comment_id);
    }

    /*
     * @param int $comment_id
     * @return json $data
     */
    public function togglePrivate($comment_id)
    {
        return $this->comment_api->togglePrivate($comment_id);
    }
}
