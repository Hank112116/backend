<?php namespace Backend\Logger;

use HipChat\HipChat;
use Illuminate\Auth\AuthManager;

class HipChatLogger implements LoggerInterface
{
    private $hipchat;
    private $auth;

    public function __construct(HipChat $hipchat, AuthManager $auth)
    {
        $this->hipchat = $hipchat;
        $this->auth = $auth;
    }

    public function error($env, $msg)
    {
        $user = $this->userName();

        $name = "[{$env}][{$user}]";

        $msg = mb_substr($msg, 0, 1000);

        $this->hipchat->message_room('Hwtrek-Backend-Log', $name, $msg);
    }

    private function userName()
    {
        if ($this->auth->check()) {
            return $this->auth->user()->name;
        }

        return 'N';
    }
}
