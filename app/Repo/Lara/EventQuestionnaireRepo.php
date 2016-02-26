<?php namespace Backend\Repo\Lara;

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
