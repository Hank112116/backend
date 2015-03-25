<?php

namespace spec\Backend\ErrorReport;

use PhpSpec\Laravel\LaravelObjectBehavior;
use Prophecy\Argument;

use Illuminate\Encryption\Encrypter;
use Backend\Logger\LoggerInterface;

class ReporterSpec extends LaravelObjectBehavior
{
    function let(Encrypter $encrypter, LoggerInterface $logger)
    {
        $this->beConstructedWith($encrypter, $logger);
    }

    function it_should_be_initialized()
    {
        $this->shouldHaveType('Backend\ErrorReport\Reporter');
    }

    function it_should_encrypt_exception_and_call_logger(Encrypter $encrypter, LoggerInterface $logger)
    {
        $str = Argument::type('string');

        $encrypter->encrypt($str)->willReturn('encrypted-message');
        $logger->error($str, $str)->shouldBeCalled();
        $this->send(new \Exception(''));
    }

    function it_should_decrypt_a_encrypted_msg(Encrypter $encrypter)
    {
        $encrypter->decrypt('encrypted-message')->shouldBeCalled();
        $this->decrypt('encrypted-message');
    }

}
