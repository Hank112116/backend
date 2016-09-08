<?php namespace Backend\Repo\Lara;

use Carbon;
use Backend\Model\Eloquent\EventApplication;
use Backend\Repo\RepoInterfaces\EventApplicationInterface;
use Backend\Repo\RepoTrait\PaginateTrait;
use Illuminate\Database\Eloquent\Collection;

class EventApplicationRepo implements EventApplicationInterface
{
    const DEFAULT_INTERNAL_MEMO = [
        'note_info'      => [
            'note'       => null,
            'operator'   => null,
            'updated_at' => null
        ],
        'follow_pm' => null,
        'internal_set_status' => [
            'status'     => null,
            'operator'   => null,
            'updated_at' => null
        ],
        'internal_set_form_status' => [
            'status'     => null,
            'operator'   => null,
            'updated_at' => null
        ]
    ];
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
                if ($approve_event_user[0]->isTour()) {
                    $result[] = $approve_event_user[0];
                }
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

    public function updateEventMemo($id, $input)
    {
        // if event complete update same user note, else update event row note
        $event_user = $this->event->find($id);
        $memo = json_decode($event_user->note, true);

        if (empty($memo)) {
            $memo = self::DEFAULT_INTERNAL_MEMO;
        }

        if (array_key_exists('note', $input)) {
            $memo['note_info']['note']       = $input['note'];
            $memo['note_info']['operator']   = \Auth::user()->name;
            $memo['note_info']['updated_at'] = Carbon::now()->toDateTimeString();
        }

        if (array_key_exists('internal_selection', $input)) {
            if (!\Auth::user()) {
                return false;
            }
            $memo['internal_set_status']['status']     = $input['internal_selection'];
            $memo['internal_set_status']['operator']   = \Auth::user()->name;
            $memo['internal_set_status']['updated_at'] = Carbon::now()->toDateTimeString();
        }

        if (array_key_exists('internal_form_selection', $input)) {
            if (!\Auth::user()) {
                return false;
            }
            $memo['internal_set_form_status']['status']     = $input['internal_form_selection'];
            $memo['internal_set_form_status']['operator']   = \Auth::user()->name;
            $memo['internal_set_form_status']['updated_at'] = Carbon::now()->toDateTimeString();
        }

        if (array_key_exists('follow_pm', $input)) {
            $memo['follow_pm']     = $input['follow_pm'];
        }

        $event_user->note = json_encode($memo);
        return $event_user->save();
    }

    public function approveEventUser($user_id, $event_id)
    {
        $same_event_users = $this->event
            ->where('user_id', $user_id)
            ->where('event_id', $event_id)
            ->get();
        $approved_at = Carbon::now();
        foreach ($same_event_users as $user) {
            $user->approved_at = $approved_at;
            $user->save();
        }
        return true;
    }
}
