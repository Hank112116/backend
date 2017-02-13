<?php
namespace Backend\Model\Report\Entity;

use Backend\Contracts\Serializable;
use Backend\Enums\API\Response\Key\SolutionKey;
use Backend\Enums\API\Response\Key\UserKey;
use Backend\Model\Eloquent\Project;
use Backend\Model\Eloquent\Solution;
use Backend\Model\Eloquent\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class MemberMatchDetail
{
    const KEY_OPERATOR     = 'operator';
    const KEY_ACTION       = 'action';
    const KEY_OBJECT       = 'object';
    const KEY_OBJECT_ID    = 'object_id';
    const KEY_SOMEONE      = 'someone';
    const KEY_ON_TIME      = 'on_time';
    const KEY_RECEIVE_TYPE = 'receive_type';
    const KET_RECEIVER     = 'receiver';

    const OBJECT_PROJECT  = 'project';
    const OBJECT_SOLUTION = 'solution';
    const OBJECT_MEMBER   = 'member';

    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function action()
    {
        return $this->data[self::KEY_ACTION];
    }

    public function getOperatorId()
    {
        return $this->data[self::KEY_OPERATOR]->id();
    }

    public function getOperatorName()
    {
        return $this->data[self::KEY_OPERATOR]->textFullName();
    }

    public function getOperatorLink()
    {
        return $this->data[self::KEY_OPERATOR]->textFrontLink();
    }

    public function object()
    {
        return $this->data[self::KEY_OBJECT];
    }

    public function objectId()
    {
        return $this->data[self::KEY_OBJECT_ID];
    }

    public function objectName()
    {
        switch ($this->object()) {
            case self::OBJECT_MEMBER:
                $user   = new User();
                $object = $user->find($this->objectId());
                return $object->textFullName();
                break;
            case self::OBJECT_PROJECT:
                $project = new Project();
                $object = $project->find($this->objectId());
                return $object->textTitle();
                break;
            case self::OBJECT_SOLUTION:
                $solution = new Solution();
                $object   = $solution->find($this->objectId());
                return $object->textTitle();
                break;
        }
        return null;
    }

    public function objectLink()
    {
        switch ($this->object()) {
            case self::OBJECT_MEMBER:
                $user   = new User();
                $object = $user->find($this->objectId());
                return $object->textFrontLink();
                break;
            case self::OBJECT_PROJECT:
                $project = new Project();
                $object = $project->find($this->objectId());
                return $object->textFrontLink();
                break;
            case self::OBJECT_SOLUTION:
                $solution = new Solution();
                $object   = $solution->find($this->objectId());
                return $object->textFrontLink();
                break;
        }
        return null;
    }

    public function receiveType()
    {
        return $this->data[self::KEY_RECEIVE_TYPE];
    }

    public function receiverId()
    {
        return $this->data[self::KET_RECEIVER]->id();
    }

    public function receiverLink()
    {
        return $this->data[self::KET_RECEIVER]->textFrontLink();
    }

    public function receiverName()
    {
        switch ($this->receiveType()) {
            case self::OBJECT_MEMBER:
                return $this->data[self::KET_RECEIVER]->textFullName();
                break;
            case self::OBJECT_PROJECT:
                return $this->data[self::KET_RECEIVER]->textTitle();
                break;
            case self::OBJECT_SOLUTION:
                return $this->data[self::KET_RECEIVER]->textTitle();
                break;
        }
        return null;
    }

    /**
     * @return string
     */
    public function textOnTime()
    {
        $dt = Carbon::parse($this->data[self::KEY_ON_TIME]);
        return $dt->toFormattedDateString();
    }
}
