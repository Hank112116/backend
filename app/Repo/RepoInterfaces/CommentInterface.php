<?php namespace Backend\Repo\RepoInterfaces;

use Backend\Model\Eloquent\Comment;

interface CommentInterface
{
    public function find($commend_id);
    public function professionTopicsByPage($page, $limit);
    public function projectTopicsByPage($page, $limit);
    public function solutionTopicsByPage($page, $limit);

    public function deleteTopicAndThreads(Comment $topic);
    public function deleteThread(Comment $thread);
    public function togglePrivate(Comment $comment);
}
