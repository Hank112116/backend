<?php

use Laracasts\TestDummy\Factory;
use Backend\Model\Eloquent;

class CommentApiTest extends BackendTestCase
{
    private $api;
    private $topic;
    private $thread;

    public function setUp()
    {
        parent::setUp();

        $this->topic = Factory::create(Eloquent\Comment::class);
        $this->thread  = Factory::create(Eloquent\Comment::class);

        $this->thread->update([
           'main_comment' => $this->topic->comment_id
        ]);

        $this->api = App::make('Backend\Api\ApiInterfaces\CommentApiInterface');
    }

    /** @test */
    public function it_return_msg_after_delete_topic()
    {
        $response = $this->api->delete($this->topic->comment_id);
        $json = $response->getContent();
        $this->assertEquals(
            "delete topic success",
            json_decode($json, true)['msg']
        );
    }

    /** @test */
    public function it_return_msg_after_delete_thread()
    {
        $response = $this->api->delete($this->thread->comment_id);
        $json = $response->getContent();
        $this->assertEquals(
            "delete thread success",
            json_decode($json, true)['msg']
        );
    }

    /** @test */
    public function it_return_fail_after_when_comment_not_exist()
    {
        $response = $this->api->delete('no-such-id');
        $json = $response->getContent();
        $this->assertEquals(
            "No comment found",
            json_decode($json, true)['msg']
        );
    }

    /** @test */
    public function it_return_msg_after_toggle_private()
    {
        $response = $this->api->togglePrivate($this->topic->comment_id);
        $json = $response->getContent();
        $this->assertEquals(
            "updated",
            json_decode($json, true)['msg']
        );
    }
}
