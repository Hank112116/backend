<?php namespace Backend\Model\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class EventApplication extends Model
{

    const TYPE_AIT_2016_Q1 = 1;

    const EVENT_NAME = [
        self::TYPE_AIT_2016_Q1 => 'Asia Innovation Tour 2016 Q1',
    ];

    protected $table = 'event_application';
    public $timestamps = false;

    public function user()
    {
        return $this->hasOne(User::class, 'user_id', 'user_id');
    }

    public function project()
    {
        return $this->hasOne(Project::class, 'project_id', 'project_id');
    }

    public function textFullName()
    {
        return "{$this->user_name} {$this->last_name}";
    }

    public function textEventName()
    {
        $event_list = self::EVENT_NAME;
        return $event_list[$this->event_id];
    }

    public function isFullNameCoincide()
    {
        return Str::equals($this->textFullName(), $this->user->textFullName());
    }

    public function getEvents()
    {
        return self::EVENT_NAME;
    }

    public function isFinishApply()
    {
        return isset($this->applied_at);
    }

    public function getCompleteTime()
    {
        $email    = $this->email;
        $event_id = $this->event_id;
        $log_id   = $this->id;

        $log_event_model = new EventApplication();
        $log_event = $log_event_model->where('email', $email)
            ->where('event_id', $event_id)
            ->where('id', '!=', $log_id)
            ->first();
        return $log_event ? $log_event->applied_at : null;
    }
}
