<?php namespace Backend\Repo\Lara;

use Backend\Model\Eloquent\Inbox;
use Backend\Repo\RepoInterfaces\UserInterface;
use Backend\Repo\RepoTrait\PaginateTrait;
use Backend\Repo\RepoInterfaces\InboxInterface;
use Illuminate\Support\Collection;
use Mews\Purifier\Purifier;

class InboxRepo implements InboxInterface {

    use PaginateTrait;

    const SEARCH_BY_SENDER = 'sender';
    const SEARCH_BY_SENDER_ID = 'sender_id';
    const SEARCH_BY_RECEIVER = 'receiver';
    const SEARCH_BY_RECEIVER_ID = 'receiver_id';

    private $inbox;
    private $user_repo;

    public function __construct(
        Inbox $inbox,
        UserInterface $user_repo,
        Purifier $purifier
    ) {
        $this->inbox = $inbox;
        $this->user_repo = $user_repo;
        $this->purifier = $purifier;
    }

    public function topicsByPage($page = 1, $limit = 20)
    {
        $topics = $this->modelBuilder($this->inbox, $page, $limit)
            ->queryEagerLoad()
            ->queryTopic()
            ->get();

        $topics = $this->processed($topics);

        $this->setPaginateTotal($this->inbox->queryTopic()->count());

        return $this->getPaginateContainer($this->inbox, $page, $limit, $topics);
    }

    public function topicsBySearch($where, $value)
    {
        if (!$where or !$value) {
            return new Collection();
        }

        if(
            $where == self::SEARCH_BY_SENDER_ID or
            $where == self::SEARCH_BY_RECEIVER_ID
        ) {
            $ids = [$value];
        } else {
            $ids = $this->user_repo->byName($value)->lists('user_id');
        }

        if (count($ids) == 0) {
            return new Collection();
        }

        switch ($where) {
            case self::SEARCH_BY_SENDER :
            case self::SEARCH_BY_SENDER_ID :
                $where_column = 'sender_id';
                break;

            case self::SEARCH_BY_RECEIVER :
            case self::SEARCH_BY_RECEIVER_ID :
                $where_column = 'receiver_id';
                break;
        }

        return $this->processed($this->inbox
            ->queryEagerLoad()
            ->queryTopic()
            ->whereIn($where_column, $ids)
            ->get());
    }

    public function bySender($sender_id)
    {

    }

    public function byReceiver($receiver_id)
    {

    }

    private function processed(Collection $inboxes)
    {
        $inboxes->each(function ($inbox) {
            $inbox->message_content = nl2br($this->purifier->clean($inbox->message_content));
            if($inbox->threads->count() > 0) {
                $inbox->threads = $this->processed($inbox->threads);
            }
        });

        return $inboxes;
    }

    public function delete($message_id)
    {
        $this->inbox->find($message_id)->delete();
        $this->inbox->where('reply_message_id', $message_id)->delete();
    }
}