<?php

namespace spec\Backend\Logger;

use PhpSpec\Laravel\LaravelObjectBehavior;
use Prophecy\Argument;

use HipChat\HipChat;

class HipChatLoggerSpec extends LaravelObjectBehavior
{
    function let(HipChat $hipchat)
    {
        $this->beConstructedWith($hipchat, $this->laravel->app['auth']);
    }

    function it_should_be_initialized() {
        $this->shouldHaveType('Backend\Logger\HipChatLogger');
    }

    function it_should_send_error_by_hipchat_message_room(HipChat $hipchat) {
        $str = Argument::type('string');

        $hipchat->message_room($str, $str, $str)->shouldBeCalled();
        $this->error('test', 'msg');
    }

}
