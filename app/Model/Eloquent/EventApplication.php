<?php namespace Backend\Model\Eloquent;

use Backend\Enums\EventEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Carbon;

class EventApplication extends Model
{
    const INTERNAL_SELECTED_STATUS    = 'selected';
    const INTERNAL_CONSIDERING_STATUS = 'considering';
    const INTERNAL_REJECTED_STATUS    = 'rejected';
    const INTERNAL_PREMIUM_STATUS     = 'premium';
    const INTERNAL_EXPERT_STATUS      = 'expert';

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
        if ($this->last_name) {
            return "{$this->user_name} {$this->last_name}";
        } else {
            return $this->user_name;
        }
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

    public function isFormSent()
    {
        return isset($this->approved_at);
    }

    public function isTour()
    {
        if (!$this->user) {
            return false;
        }

        if ($this->user->isCreator() and !$this->user->isPendingExpert() or $this->project_id) {
            return true;
        } else {
            return false;
        }
    }

    public function isInternalSelected()
    {
        return $this->getInternalSetStatus() === self::INTERNAL_SELECTED_STATUS ? true : false;
    }

    public function isInternalConsidering()
    {
        return $this->getInternalSetStatus() === self::INTERNAL_CONSIDERING_STATUS ? true : false;
    }

    public function isInternalRejected()
    {
        return $this->getInternalSetStatus() === self::INTERNAL_REJECTED_STATUS ? true : false;
    }

    public function isInternalPremium()
    {
        return $this->getInternalSetStatus() === self::INTERNAL_PREMIUM_STATUS ? true : false;
    }

    public function isInternalExpert()
    {
        return $this->getInternalSetStatus() === self::INTERNAL_EXPERT_STATUS ? true : false;
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
        if (is_null($memo)) {
            return [];
        }
        return $memo['trip_participation'];
    }

    public function getEstablishedSince()
    {
        $memo = json_decode($this->message, true);

        if (is_null($memo)) {
            return null;
        }
        return $memo['established_since'];
    }

    public function getNote()
    {
        $memo = json_decode($this->note, true);
        return $memo['note_info']['note'];
    }

    public function getNoteOperator()
    {
        $memo = json_decode($this->note, true);
        return $memo['note_info']['operator'];
    }

    public function getNoteUpdatedAt()
    {
        $memo = json_decode($this->note, true);

        if ($memo['note_info']['updated_at']) {
            return Carbon::parse($memo['note_info']['updated_at'])->toFormattedDateString();
        } else {
            return null;
        }
    }

    public function getFollowPM()
    {
        $memo = json_decode($this->note, true);
        return $memo['follow_pm'];
    }

    public function getInternalSetFormStatus()
    {
        $memo = json_decode($this->note, true);

        if (empty($memo['internal_set_form_status'])) {
            return null;
        }

        return $memo['internal_set_form_status']['status'];
    }


    public function getInternalSetFormStatusOperator()
    {
        $memo = json_decode($this->note, true);

        if (empty($memo['internal_set_form_status'])) {
            return null;
        }

        return $memo['internal_set_form_status']['operator'];
    }

    public function getInternalSetFormStatusUpdatedAt()
    {
        $memo = json_decode($this->note, true);

        if (empty($memo['internal_set_form_status'])) {
            return null;
        }

        if ($memo['internal_set_form_status']['updated_at']) {
            return Carbon::parse($memo['internal_set_form_status']['updated_at'])->toFormattedDateString();
        } else {
            return null;
        }
    }

    public function getInternalSetStatus()
    {
        $memo = json_decode($this->note, true);

        return $memo['internal_set_status']['status'];
    }

    public function getTextInternalSetStatus()
    {
        switch($this->getInternalSetStatus()) {
            case self::INTERNAL_SELECTED_STATUS:
                return 'Selected';
            break;
            case self::INTERNAL_CONSIDERING_STATUS:
                return 'Considering';
            break;
            case self::INTERNAL_REJECTED_STATUS:
                return 'Rejected';
            break;
            case self::INTERNAL_PREMIUM_STATUS:
                return 'Premium';
            break;
            case self::INTERNAL_EXPERT_STATUS:
                return 'Expert';
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
