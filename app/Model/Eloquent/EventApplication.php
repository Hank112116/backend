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
        $event_list = self::EVENT_NAME;
        return $event_list[$this->event_id];
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
        return self::EVENT_NAME;
    }

    public function isFinished()
    {
        return isset($this->applied_at);
    }

    public function isSelected()
    {
        return isset($this->approved_at);
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
            ->first();
        return $event ? $event->applied_at : null;
    }

    public function getQuestionnaire()
    {
        return $this->questionnaire()->where('subject_id', $this->event_id)->orderBy('id', 'DESC')->first();
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
}
