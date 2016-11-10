<?php

use Backend\Model\Eloquent;
use Backend\Model\Eloquent\Comment;
use Laracasts\TestDummy\Factory;
use Backend\Repo\Lara\CommentRepo;

class CommentRepoTest extends BackendTestCase
{
    private $repo;

    public function setUp()
    {
        parent::setUp();
        $this->repo = app()->make('Backend\Repo\RepoInterfaces\CommentInterface');
    }


    /** @test */
    public function it_fetches_profession_topics_by_page()
    {
        $user = Factory::create(Eloquent\User::class);

        Factory::times(1)->create('Thread');
        $comments = Factory::times(2)->create(Eloquent\Comment::class);
        $comments->first()->update(['profession_id' => $user->user_id]);

        $this->assertEquals(1, $this->repo->professionTopicsByPage()->count());
    }

    /** @test */
    public function it_fetches_profession_topics_by_search_name()
    {
        $profession = Factory::create(Eloquent\User::class, [
            'user_name' => 'PROFESSION_NAME'
        ]);

        $topic_owner = Factory::create(Eloquent\User::class, [
            'user_name' => 'TOPIC_OWNER_NAME'
        ]);

        Factory::times(1)->create('Thread');
        $comments = Factory::times(2)->create(Eloquent\Comment::class);

        $comments->first()->update([
            'profession_id' => $profession->user_id,
            'user_id'       => $topic_owner->user_id
        ]);

        $fetched = $this->repo->professionTopicsBySearch(CommentRepo::SEARCH_BY_TOPIC_CREATOR, 'TOPIC_OWNER_NAME');
        $this->assertEquals(1, $fetched->count());

        $fetched = $this->repo->professionTopicsBySearch(CommentRepo::SEARCH_BY_PROFESSION_NAME, 'PROFESSION_NAME');
        $this->assertEquals(1, $fetched->count());
    }

    /** @test */
    public function it_fetches_project_topics_by_page()
    {
        $project = Factory::create(Eloquent\Project::class);

        Factory::times(1)->create('Thread');
        $comments = Factory::times(2)->create(Eloquent\Comment::class);
        $comments->first()->update(['project_id' => $project->project_id]);

        $this->assertEquals(1, $this->repo->projectTopicsByPage()->count());
    }

    /** @test */
    public function it_fetches_project_topics_by_search_name()
    {
        $topic_owner = Factory::create(Eloquent\User::class, [
            'user_name' => 'TOPIC_OWNER_NAME'
        ]);

        $project_owner = Factory::create(Eloquent\User::class, [
            'user_name' => 'PROJECT_OWNER_NAME'
        ]);

        $project = Factory::create(Eloquent\Project::class, [
            'user_id'       => $project_owner->user_id,
            'project_title' => 'PROJECT_TITLE'
        ]);

        Factory::times(1)->create('Thread');
        $comments = Factory::times(2)->create(Eloquent\Comment::class);

        $comments->first()->update([
            'project_id' => $project->project_id,
            'user_id'    => $topic_owner->user_id
        ]);

        $fetched = $this->repo->projectTopicsBySearch(CommentRepo::SEARCH_BY_TOPIC_CREATOR, 'TOPIC_OWNER_NAME');
        $this->assertEquals(1, $fetched->count());

        $fetched = $this->repo->projectTopicsBySearch(CommentRepo::SEARCH_BY_TITLE, 'PROJECT_TITLE');
        $this->assertEquals(1, $fetched->count());

        $fetched = $this->repo->projectTopicsBySearch(CommentRepo::SEARCH_BY_OWNER, 'PROJECT_OWNER_NAME');
        $this->assertEquals(1, $fetched->count());
    }

    /** @test */
    public function it_fetches_solution_topics_by_page()
    {
        $solution = Factory::create(Eloquent\Solution::class);

        Factory::times(1)->create('Thread');
        $comments = Factory::times(2)->create(Eloquent\Comment::class);
        $comments->first()->update(['solution_id' => $solution->solution_id]);

        $this->assertEquals(1, $this->repo->solutionTopicsByPage()->count());
    }

    /** @test */
    public function it_fetches_solution_topics_by_search_name()
    {
        $topic_owner = Factory::create(Eloquent\User::class, [
            'user_name' => 'TOPIC_OWNER_NAME'
        ]);

        $project_owner = Factory::create(Eloquent\User::class, [
            'user_name' => 'SOLUTION_OWNER_NAME'
        ]);

        $solution = Factory::create(Eloquent\Solution::class, [
            'user_id'        => $project_owner->user_id,
            'solution_title' => 'SOLUTION_TITLE'
        ]);

        Factory::times(1)->create('Thread');
        $comments = Factory::times(2)->create(Eloquent\Comment::class);

        $comments->first()->update([
            'solution_id' => $solution->solution_id,
            'user_id'     => $topic_owner->user_id
        ]);

        $fetched = $this->repo->solutionTopicsBySearch(CommentRepo::SEARCH_BY_TOPIC_CREATOR, 'TOPIC_OWNER_NAME');
        $this->assertEquals(1, $fetched->count());

        $fetched = $this->repo->solutionTopicsBySearch(CommentRepo::SEARCH_BY_TITLE, 'SOLUTION_TITLE');
        $this->assertEquals(1, $fetched->count());

        $fetched = $this->repo->solutionTopicsBySearch(CommentRepo::SEARCH_BY_OWNER, 'SOLUTION_OWNER_NAME');
        $this->assertEquals(1, $fetched->count());
    }

    /** @test */
    public function it_deletes_topics_and_its_threads()
    {
        $topic = Factory::create(Eloquent\Comment::class);
        Factory::times(3)->create(Eloquent\Comment::class, [
            'main_comment' => $topic->comment_id
        ]);

        $preserved_topic = Factory::create(Eloquent\Comment::class);
        Factory::create(Eloquent\Comment::class, [
            'main_comment' => $preserved_topic->comment_id
        ]);

        $this->repo->deleteTopicAndThreads($topic);
        $this->assertEquals(2, Comment::all()->count());
    }
}
