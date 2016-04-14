<?php namespace Backend\Repo\Lara;

use Carbon;
use Backend\Model\Eloquent\EventApplication;
use Backend\Repo\RepoInterfaces\EventApplicationInterface;
use Backend\Repo\RepoTrait\PaginateTrait;
use Illuminate\Database\Eloquent\Collection;

class EventApplicationRepo implements EventApplicationInterface
{
    use PaginateTrait;

    private $event;

    public function __construct(EventApplication $event)
    {
        $this->event = $event;
    }

    public function all()
    {
        return $this->event->orderBy('event_id', 'DESC')->orderBy('id', 'DESC')->get();
    }

    public function findByEventId($event_id)
    {
        return $this->event->where('event_id', $event_id)->orderBy('id', 'DESC')->get();
    }

    public function findByUserId($user_id)
    {
        return $this->event->where('user_id', $user_id)->get();
    }

    public function findApproveEventUsers($event_id)
    {
        $result = [];
        $approve_event_users =  $this->event->where('event_id', $event_id)
            ->whereNotNull('approved_at')
            ->where('user_id', '!=', 0)
            ->orderBy('id', 'DESC')->get();
        $approve_event_users = $approve_event_users->groupBy('user_id');
        if ($approve_event_users) {
            foreach ($approve_event_users as $approve_event_user) {
                $result[] = $approve_event_user[0];
            }
        }
        return Collection::make($result);
    }


    public function getEvents()
    {
        return $this->event->getEvents();
    }

    public function getDefaultEvent()
    {
        $event_list = $this->getEvents();
        $keys       = array_keys($event_list);
        return end($keys);
    }

    public function byCollectionPage($collection, $page = 1, $per_page = 50)
    {
        return $this->getPaginateFromCollection($collection, $page, $per_page);
    }

    public function updateEventNote($id, $note)
    {
        // if event complete update same user note, else update event row note
        $event_user = $this->event->find($id);

        if ($event_user->user_id === 0) {
            $event_user->note = $note;
            return $event_user->save();
        }

        $same_event_users = $this->findByUserId($event_user->user_id);

        if ($same_event_users) {
            foreach ($same_event_users as $user) {
                $user->note = $note;
                $user->save();
            }
        }
        return true;
    }

    public function approveEventUser($user_id)
    {
        $same_event_users = $this->findByUserId($user_id);
        $approved_at = Carbon::now();
        foreach ($same_event_users as $user) {
            $user->approved_at = $approved_at;
            $user->save();
        }
        return true;
    }
}
