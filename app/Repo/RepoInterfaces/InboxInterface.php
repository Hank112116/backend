<?php namespace Backend\Repo\RepoInterfaces;

interface InboxInterface
{
    public function topicsByPage($page, $limit);
    public function topicsBySearch($where, $value);

    public function bySender($sender_id);
    public function byReceiver($receiver_id);

    public function delete($message_id);
}
