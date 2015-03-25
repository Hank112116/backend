<?php namespace Backend\Api\Lara;

use Backend\Repo\RepoInterfaces\CommentInterface;
use Backend\Api\ApiInterfaces\CommentApiInterface;

class CommentApi extends BaseApi implements CommentApiInterface
{
    public function __construct(CommentInterface $comment_repo)
    {
        $this->comment_repo = $comment_repo;
    }

    public function delete($comment_id)
    {
        $comment = $this->comment_repo->find($comment_id);
        if (!$comment) {
            return $this->json_fail('No comment found');
        }

        if ($comment->isTopic()) {
            $this->comment_repo->deleteTopicAndThreads($comment);

            return $this->json_ok(['msg' => 'delete topic success']);
        }

        $this->comment_repo->deleteThread($comment);

        return $this->json_ok(['msg' => 'delete thread success']);
    }

    public function togglePrivate($comment_id)
    {
        $comment = $this->comment_repo->find($comment_id);
        if (!$comment) {
            return $this->json_fail('No comment found');
        }

        $this->comment_repo->togglePrivate($comment);

        return $this->json_ok(['msg' => 'updated']);
    }
}
