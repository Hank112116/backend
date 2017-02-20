<?php namespace Backend\Model\Eloquent;

use Illuminate\Database\Eloquent\Model;

class EventQuestionnaireFeedback extends Model
{
    protected $table = 'event_questionnaire_feedback';
    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
