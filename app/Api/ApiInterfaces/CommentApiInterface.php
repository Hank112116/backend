<?php namespace Backend\Api\ApiInterfaces;

interface CommentApiInterface
{
    public function delete($comment_id);
    public function togglePrivate($comment_id);
}
