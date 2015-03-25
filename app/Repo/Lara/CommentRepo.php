<?php
namespace Backend\Repo\Lara;

use Backend\Model\Eloquent\Comment;
use Backend\Repo\RepoInterfaces\CommentInterface;
use Backend\Repo\RepoInterfaces\UserInterface;
use Backend\Repo\RepoInterfaces\ProjectInterface;
use Backend\Repo\RepoInterfaces\ProductInterface;
use Backend\Repo\RepoInterfaces\SolutionInterface;
use Backend\Repo\RepoTrait\PaginateTrait;
use Illuminate\Support\Collection;

class CommentRepo implements CommentInterface
{
    use PaginateTrait;

    const SEARCH_BY_TOPIC_CREATOR = 'topic_creator';
    const SEARCH_BY_TITLE = 'title';
    const SEARCH_BY_OWNER = 'owner';
    const SEARCH_BY_PROFESSION_NAME = 'profession_name';

    public function __construct(
        Comment $comment,
        UserInterface $user_repo,
        ProjectInterface $project_repo,
        ProductInterface $product_repo,
        SolutionInterface $solution_repo
    ) {
        $this->comment = $comment;

        $this->user_repo     = $user_repo;
        $this->project_repo  = $project_repo;
        $this->prodcut_repo  = $product_repo;
        $this->solution_repo = $solution_repo;
    }

    public function find($comment_id)
    {
        return $this->comment->find($comment_id);
    }

    public function professionTopicsByPage($page = 1, $limit = 20)
    {
        $comments = $this->modelBuilder($this->comment, $page, $limit)
            ->queryEagerLoad()
            ->queryTopic()
            ->queryProfession()
            ->get();

        $this->setPaginateTotal($this->comment->queryTopic()->queryProfession()->count());

        return $this->getPaginateContainer($this->comment, $page, $limit, $comments);
    }

    public function professionTopicsBySearch($where, $value)
    {
        if (!$where or !$value) {
            return new Collection();
        }

        $ids = $this->user_repo->byName($value)->lists('user_id');

        switch ($where) {
        case self::SEARCH_BY_TOPIC_CREATOR :
            $where_column = 'user_id';
            break;

        case self::SEARCH_BY_PROFESSION_NAME :
            $where_column = 'profession_id';
            break;
        }

        if (count($ids) == 0) {
            return new Collection();
        }

        return $this->comment->queryEagerLoad()
            ->queryTopic()
            ->queryProfession()
            ->whereIn($where_column, $ids)
            ->get();
    }

    public function projectTopicsByPage($page = 1, $limit = 20)
    {
        $comments = $this->modelBuilder($this->comment, $page, $limit)
            ->queryEagerLoad()
            ->queryTopic()
            ->queryProject()
            ->get();

        $this->setPaginateTotal($this->comment->queryTopic()->queryProject()->count());

        return $this->getPaginateContainer($this->comment, $page, $limit, $comments);
    }

    public function projectTopicsBySearch($where, $value)
    {
        if (!$where or !$value) {
            return new Collection();
        }

        switch ($where) {
        case self::SEARCH_BY_TOPIC_CREATOR :
            $ids = $this->user_repo->byName($value)->lists('user_id');
            $where_column = 'user_id';
            break;

        case self::SEARCH_BY_TITLE :
            $ids = array_merge(
                $this->project_repo->byTitle($value)->lists('project_id'),
                $this->prodcut_repo->byTitle($value)->lists('project_id')
            );

            $where_column = 'project_id';
            break;

        case self::SEARCH_BY_OWNER :
            $ids = array_merge(
                $this->project_repo->byUserName($value)->lists('project_id'),
                $this->prodcut_repo->byUserName($value)->lists('project_id')
            );
            $where_column = 'project_id';
            break;
        }

        if (count($ids) == 0) {
            return new Collection();
        }

        return $this->comment->queryEagerLoad()
            ->queryTopic()
            ->queryProject()
            ->whereIn($where_column, $ids)
            ->get();
    }

    public function solutionTopicsByPage($page = 1, $limit = 20)
    {
        $comments = $this->modelBuilder($this->comment, $page, $limit)
            ->queryEagerLoad()
            ->queryTopic()
            ->querySolution()
            ->get();

        $this->setPaginateTotal($this->comment->queryTopic()->querySolution()->count());

        return $this->getPaginateContainer($this->comment, $page, $limit, $comments);
    }

    public function solutionTopicsBySearch($where, $value)
    {
        if (!$where or !$value) {
            return new Collection();
        }

        switch ($where) {
        case self::SEARCH_BY_TOPIC_CREATOR :
            $ids = $this->user_repo->byName($value)->lists('user_id');
            $where_column = 'user_id';
            break;

        case self::SEARCH_BY_TITLE :
            $ids = $this->solution_repo->byTitle($value)->lists('solution_id');
            $where_column = 'solution_id';
            break;

        case self::SEARCH_BY_OWNER :
            $ids = $this->solution_repo->byUserName($value)->lists('solution_id');
            $where_column = 'solution_id';
            break;
        }

        if (count($ids) == 0) {
            return new Collection();
        }

        return $this->comment->queryEagerLoad()
            ->queryTopic()
            ->querySolution()
            ->whereIn($where_column, $ids)
            ->get();
    }

    public function deleteTopicAndThreads(Comment $topic)
    {
        $topic->delete();

        $this->comment
            ->where('main_comment', $topic->comment_id)
            ->delete();
    }

    public function deleteThread(Comment $thread)
    {
        $thread->delete();
    }

    public function togglePrivate(Comment $comment)
    {
        $comment->private_comment = ($comment->private_comment == 1) ? 0 : 1;
        $comment->save();
    }
}
