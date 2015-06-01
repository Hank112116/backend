<?php

namespace Backend\Http\Controllers;

use Backend\Repo\RepoInterfaces\InboxInterface;
use Input;
use Response;

class InboxController extends BaseController
{

    protected $cert = 'user';

    private $inbox_repo;

    public function __construct(InboxInterface $inbox_repo)
    {
        parent::__construct();

        $this->inbox_repo = $inbox_repo;
        $this->per_page   = 20;
    }

    /**
     * Render index page
     *
     * @return Response
     */
    public function index()
    {
        return view('inbox.topics');
    }

    /**
     * Fetch topics
     *
     * @return Response : topics json
     */
    public function topics()
    {
        return Response::json(
            $this->inbox_repo->topicsByPage($this->page, $this->per_page)
        );
    }

    public function search()
    {
        $where  = Input::get('where', '');
        $search = Input::get('search', '');

        return Response::json([
            'data' => $this->inbox_repo->topicsBySearch($where, $search)
        ]);
    }

    /**
     * Delete
     *
     * @param int $message_id
     */
    public function delete($message_id)
    {
        $this->inbox_repo->delete($message_id);
    }
}
