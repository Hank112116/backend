<?php

use Backend\Model\Eloquent;
use Backend\Model\Eloquent\MailTemplate;
use Laracasts\TestDummy\Factory;

class MailTemplateRepoTest extends BackendTestCase
{
    private $repo;

    public function setUp()
    {
        parent::setUp();
        $this->repo = App::make('Backend\Repo\RepoInterfaces\MailTemplateInterface');
    }

    /** @test */
    public function it_get_templates_by_active()
    {
        $templates = Factory::times(3)->create(Eloquent\MailTemplate::class);
        $templates->first()->update(['active' => 0]);

        $actived = $this->repo->byActive($active = true);
        $this->assertEquals(2, $actived->count());

        $archived = $this->repo->byActive($active = false);
        $this->assertEquals(1, $archived->count());
    }

    /** @test */
    public function it_parses_tags_from_templates()
    {
        Factory::create(Eloquent\MailTemplate::class, [
            'message' => '{foo} mail content ...'
        ]);

        Factory::create(Eloquent\MailTemplate::class, [
            'message' => '{bar} mail {foo} content {zoo} ...'
        ]);


        $this->assertEquals(
            ['{bar}', '{foo}', '{zoo}'],
            $this->repo->getTags()
        );
    }

    /** @test */
    public function it_create_template()
    {
        $this->repo->create(['message' => 'FOO']);

        $this->assertEquals('FOO', MailTemplate::first()->message);
    }

    /** @test */
    public function it_update_template()
    {
        $template = Factory::create(Eloquent\MailTemplate::class);
        $this->repo->update($template->email_template_id, [
            'message' => 'FOO'
        ]);

        $this->assertEquals('FOO', MailTemplate::find($template->email_template_id)->message);
    }

    /** @test */
    public function it_switch_template_active()
    {
        $template = Factory::create(Eloquent\MailTemplate::class);

        $this->repo->switchActive($template->email_template_id);
        $this->assertTrue($template->active == !MailTemplate::find($template->email_template_id)->active);
    }
}
