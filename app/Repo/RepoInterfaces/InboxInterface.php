<?php namespace Backend\Repo\RepoInterfaces;

use Backend\Model\Eloquent\Comment;

interface InboxInterface
{
    public function topicsByPage($page, $limit);
    public function topicsBySearch($where, $value);

    public function bySender($sender_id);
    public function byReceiver($receiver_id);

    public function delete($message_id);
    public function deleteByCommentId($comment_id);
    public function deleteRespondCommentThread(Comment $thread);
}
