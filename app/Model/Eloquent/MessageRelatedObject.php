<?php

namespace Backend\Model\Eloquent;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Carbon;

class MessageRelatedObject extends Eloquent
{
    const TYPE_PM_ATTACH  = 'pm-attach';
    const TYPE_ATTACH     = 'attach';
    const TYPE_PM_PROPOSE = 'pm-propose';
    const TYPE_PROPOSE    = 'propose';

    const TYPE_SOLUTION   = 'solution';
    const TYPE_PROJECT    = 'project';

    protected $table = 'log_message_related_object_event';
    protected $primaryKey = 'log_id';

    private $user_column = [
        'user_id', 'user_name', 'last_name', 'user_type',
        'is_sign_up_as_expert', 'is_apply_to_be_expert',
        'company', 'active', 'company_url', 'suspended_at',
        'user_role', 'email_verify'
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id', 'user_id')
            ->select($this->user_column);
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id', 'user_id')
            ->select($this->user_column);
    }

    public function object()
    {
        switch ($this->objectType()) {
            case self::TYPE_PROJECT:
                return $this->belongsTo(Project::class, 'object_id', 'project_id')
                    ->select('project_id', 'uuid', 'user_id', 'project_title');
                break;
            case self::TYPE_SOLUTION:
                return $this->belongsTo(Solution::class, 'object_id', 'solution_id')
                    ->select('solution_id', 'user_id', 'solution_title');
                break;
                break;
        }
    }

    /**
     * @return int
     */
    public function objectId()
    {
        return $this->object_id;
    }

    /**
     * @return string
     */
    public function objectType()
    {
        return $this->object_type;
    }


    public function objectOwner()
    {
        return $this->object->owner;
    }

    /**
     * @return string
     */
    public function attachedAt()
    {
        return $this->attached_at;
    }

    /**
     * @return string
     */
    public function textAttachedAt()
    {
        $dt = Carbon::parse($this->attachedAt());
        return $dt->toFormattedDateString();
    }

    /**
     * @return bool
     */
    public function isPMAttach()
    {
        return $this->type === self::TYPE_PM_ATTACH;
    }

    /**
     * @return bool
     */
    public function isUserAttach()
    {
        return $this->type === self::TYPE_ATTACH;
    }

    /**
     * @return bool
     */
    public function isPMAttachSolution()
    {
        return $this->type === self::TYPE_PM_ATTACH and $this->object_type === self::TYPE_SOLUTION;
    }

    /**
     * @return bool
     */
    public function isUserAttachSolution()
    {
        return $this->type === self::TYPE_ATTACH and $this->object_type === self::TYPE_SOLUTION;
    }

    /**
     * @return bool
     */
    public function isPMAttachProject()
    {
        return $this->type === self::TYPE_PM_ATTACH and $this->object_type === self::TYPE_PROJECT;
    }

    /**
     * @return bool
     */
    public function isUserAttachProject()
    {
        return $this->type === self::TYPE_ATTACH and $this->object_type === self::TYPE_PROJECT;
    }

    /**
     * @return bool
     */
    public function isPMProposeSolution()
    {
        return $this->type === self::TYPE_PM_PROPOSE and $this->object_type === self::TYPE_SOLUTION;
    }

    /**
     * @return bool
     */
    public function isUserProposeSolution()
    {
        return $this->type === self::TYPE_PROPOSE and $this->object_type === self::TYPE_SOLUTION;
    }

    /**
     * @return bool
     */
    public function isPMProposeProject()
    {
        return $this->type === self::TYPE_PM_PROPOSE and $this->object_type === self::TYPE_PROJECT;
    }

    /**
     * @return bool
     */
    public function isUserProposeProject()
    {
        return $this->type === self::TYPE_PROPOSE and $this->object_type === self::TYPE_PROJECT;
    }

    public function getMessageTopicTag()
    {
        $message_group = \DB::table('message')
            ->join('message_group', 'message.group_id', '=', 'message_group.id')
            ->where('message.id', $this->message_id)
            ->first();
        $message_topic = \DB::table('message_topic')
            ->where('id', $message_group->topic_id)
            ->first();

        return $message_topic['tag'];
    }
}
