<?php namespace Backend\Model\Eloquent;

use Backend\Enums\EventEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Carbon;

class EventApplication extends Model
{
    protected $table   = 'event_application';
    public $timestamps = false;

    public function user()
    {
        return $this->hasOne(User::class, 'user_id', 'user_id');
    }

    public function project()
    {
        return $this->hasOne(Project::class, 'project_id', 'project_id');
    }

    public function questionnaire()
    {
        return $this->hasMany(EventQuestionnaireFeedback::class, 'user_id', 'user_id');
    }

    public function textFullName()
    {
        return "{$this->user_name} {$this->last_name}";
    }

    public function textEventName()
    {
        $event_list = EventEnum::EVENT_NAME;
        return $event_list[$this->event_id];
    }
    
    public function textApplyTime()
    {
        if ($this->applied_at) {
            return Carbon::parse($this->applied_at)->toFormattedDateString();
        } else {
            return Carbon::parse($this->entered_at)->toFormattedDateString();
        }
    }
    
    public function textEnterTime()
    {
        if ($this->entered_at) {
            return Carbon::parse($this->entered_at)->toFormattedDateString();
        } else {
            return null;
        }
    }

    public function isCoincide()
    {
        $full_name_flag = Str::equals($this->textFullName(), $this->user->textFullName());
        $email_flag     = Str::equals($this->email, $this->user->email);
        $company_flag   = Str::equals($this->company, $this->user->company);
        $position_flag  = Str::equals($this->job_title, $this->user->business_id);
        return $full_name_flag && $email_flag && $company_flag && $position_flag;
    }

    public function getEvents()
    {
        return EventEnum::EVENT_NAME;
    }

    public function isFinished()
    {
        return isset($this->applied_at);
    }

    public function isDropped()
    {
        if (is_null($this->applied_at) and $this->entered_at) {
            return true;
        } else {
            return false;
        }
    }

    public function isSelected()
    {
        return isset($this->approved_at);
    }

    public function isTour()
    {
        if ($this->user->isCreator() and !$this->user->isPendingExpert() or $this->project_id) {
            return true;
        } else {
            return false;
        }
    }

    public function getCompleteTime()
    {
        $email    = $this->email;
        $event_id = $this->event_id;
        $id   = $this->id;

        $event_model = new EventApplication();
        $event = $event_model->where('email', $email)
            ->where('event_id', $event_id)
            ->where('id', '!=', $id)
            ->orderBy('id', 'DESC')
            ->first();
        return $event ?  Carbon::parse($event->applied_at)->toFormattedDateString() : null;
    }

    public function getQuestionnaire()
    {
        return $this->questionnaire()->where('subject_id', $this->event_id)
            ->where('user_id', $this->user_id)
            ->orderBy('id', 'DESC')->first();
    }

    public function getApplyCount()
    {
        $event_model = new EventApplication();

        $count = $event_model->where('event_id', $this->event_id)
            ->where('user_id', $this->user_id)
            ->where('applied_at', '!=', '')
            ->count();
        return $count;
    }

    public function hasGuestJoin()
    {
        $memo = json_decode($this->message, true);
        $other_join = $memo['other_join'];
        if ($other_join['email'] or $other_join['full_name'] or $other_join['job_title']) {
            return true;
        } else {
            return false;
        }
    }

    public function getMessage()
    {
        $memo = json_decode($this->message, true);
        return $memo['message'];
    }

    public function getGuestInfo()
    {
        $memo = json_decode($this->message, true);
        return $memo['other_join'];
    }

    public function getTripParticipation()
    {
        $memo = json_decode($this->message, true);
        return $memo['trip_participation'];
    }

    public function getNote()
    {
        $memo = json_decode($this->note, true);
        return $memo['note'];
    }

    public function getFollowPM()
    {
        $memo = json_decode($this->note, true);
        return $memo['follow_pm'];
    }

    public function getInternalSetStatus()
    {
        $memo = json_decode($this->note, true);

        return $memo['internal_set_status']['status'];
    }

    public function getTextInternalSetStatus()
    {
        switch($this->getInternalSetStatus()) {
            case 'selected':
                return 'Selected';
            break;
            case 'considering':
                return 'Considering';
            break;
            case 'rejected':
                return 'Rejected';
            break;
            default:
                return 'N/A';
        }
    }

    public function getInternalSetStatusOperator()
    {
        $memo = json_decode($this->note, true);
        return $memo['internal_set_status']['operator'];
    }

    public function getInternalSetStatusUpdatedAt()
    {
        $memo = json_decode($this->note, true);

        if ($memo['internal_set_status']['updated_at']) {
            return Carbon::parse($memo['internal_set_status']['updated_at'])->toFormattedDateString();
        } else {
            return null;
        }
    }

    public function getTextTicketType()
    {
        if ($this->isTour()) {
            return 'AIT';
        } else {
            $trips = $this->getTripParticipation();
            if (empty($trips)) {
                return null;
            } else {
                $data = [];
                foreach ($trips as $trip) {
                    if ($trip === 'shenzhen') {
                        array_push($data, 'Meetup SZ');
                    } elseif ($trip === 'osaka') {
                        array_push($data, 'Meetup Osaka');
                    }
                }
                return implode('<br/>', $data);
            }
        }
    }
}
