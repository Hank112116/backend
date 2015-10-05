<?php

namespace Backend\Http\Controllers;

use Backend\Http\Controllers\BaseController;
use Illuminate\Support\Collection;
use Backend\Repo\RepoInterfaces\CommentInterface;
use Backend\Api\ApiInterfaces\CommentApiInterface;

use Input;
use Response;
use View;
use Log;

class CommentController extends BaseController
{

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
        return Response::json(
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
        return Response::json(
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
        return Response::json(
            $this->comment_repo->solutionTopicsByPage($this->page, $this->per_page)
        );
    }

    public function searchProfessions()
    {
        $where  = Input::get('where', '');
        $search = Input::get('search', '');

        return Response::json([
            'data' => $this->comment_repo->professionTopicsBySearch($where, $search)
        ]);
    }

    public function searchProjects()
    {
        $where  = Input::get('where', '');
        $search = Input::get('search', '');

        return Response::json([
            'data' => $this->comment_repo->projectTopicsBySearch($where, $search)
        ]);
    }

    public function searchSolutions()
    {
        $where  = Input::get('where', '');
        $search = Input::get('search', '');

        return Response::json([
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
            'comment' => $comment_id
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
