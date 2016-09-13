<?php namespace Backend\Repo\Lara;

use Backend\Events\Event;
use Backend\Model\Eloquent\EventApplication;
use Carbon;
use Backend\Model\Eloquent\EventQuestionnaireFeedback;
use Backend\Repo\RepoInterfaces\EventQuestionnaireInterface;
use Backend\Repo\RepoTrait\PaginateTrait;
use Backend\Enums\EventEnum;
use Illuminate\Database\Eloquent\Collection;

class EventQuestionnaireRepo implements EventQuestionnaireInterface
{
    use PaginateTrait;

    private $questionnaire;

    public function __construct(EventQuestionnaireFeedback $questionnaire)
    {
        $this->questionnaire = $questionnaire;
    }

    public function find($id)
    {
        return $this->questionnaire->find($id);
    }

    public function findByUserId($user_id)
    {
        return $this->questionnaire->where('user_id', $user_id)->get();
    }

    public function findByEventId($event_id)
    {
        $questionnaires = $this->questionnaire->where('subject_id', $event_id)->get();
        if (is_null($questionnaires)) {
            return null;
        }

        foreach ($questionnaires as $index => $row) {
            $details = json_decode($row->detail, true);
            foreach ($details as $key => $item) {
                $row->$key = $item;
            }

            $questionnaires[$index] = $row;

        }
        return $questionnaires;
    }

    public function findByApproveUser(EventApplication $user)
    {
        if (!$user->isTour()) {
            return null;
        }

        $questionnaire =  $this->questionnaire->where('subject_id', $user->event_id)
            ->where('user_id', $user->user_id)
            ->first();

        if (is_null($questionnaire)) {
            return null;
        }

        $details = json_decode($questionnaire->detail, true);

        foreach ($details as $key => $item) {
            $questionnaire->$key = $item;
        }

        switch ($user->event_id) {
            case EventEnum::TYPE_AIT_2016_Q4:
                if ($questionnaire->join_tour and $questionnaire->is_done) {
                    $questionnaire->form_status = 'Completed';
                } elseif ($questionnaire->join_tour and !$questionnaire->is_done) {
                    $questionnaire->form_status = 'Ongoing';
                } elseif (!$questionnaire->join_tour and !$questionnaire->is_done) {
                    $questionnaire->form_status = 'Rejected';
                } else {
                    $questionnaire->form_status = 'N/A';
                }
                $guest['guest_info']            = $questionnaire->guest_info;
                $guest['guest_attendee_name']   = $questionnaire->guest_attendee_name;
                $guest['guest_job_title']       = $questionnaire->guest_job_title;
                $guest['guest_email']           = $questionnaire->guest_email;
                $guest['guest_phone']           = $questionnaire->guest_phone;
                $questionnaire->guest_json = json_encode($guest);
                break;
        }
        return $questionnaire;
    }


    public function getQuestionnaireColumn($event_id)
    {
        $questionnaire_column_name = EventEnum::QUESTIONNAIRE_COLUMN_NAME;
        return $questionnaire_column_name[$event_id];
    }

    public function getView($event_id)
    {
        $views = EventEnum::QUESTIONNAIRE_VIEWS;

        if (!array_key_exists($event_id, $views)) {
            return 'errors.404';
        }
        return $views[$event_id];
    }
}
