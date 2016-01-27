<?php namespace Backend\Repo\Lara;

use Carbon;
use Backend\Model\Eloquent\EventQuestionnaireFeedback;
use Backend\Repo\RepoInterfaces\EventQuestionnaireInterface;
use Backend\Repo\RepoTrait\PaginateTrait;

class EventQuestionnaireRepo implements EventQuestionnaireInterface
{
    use PaginateTrait;

    private $questionnaire;

    public function __construct(EventQuestionnaireFeedback $questionnaire)
    {
        $this->questionnaire = $questionnaire;
    }
}
