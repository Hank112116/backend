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

    public function findApproveEventUsers($event_id)
    {
        return $this->event->where('event_id', $event_id)
            ->whereNotNull('approved_at')
            ->groupBy('user_id')
            ->orderBy('id', 'DESC')->get();
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
        $event_user = $this->event->find($id);
        $event_user->note = $note;
        return $event_user->save();
    }

    public function approveEventUser($id)
    {
        $event_user = $this->event->find($id);
        $event_user->approved_at = Carbon::now();
        return $event_user->save();
    }
}
