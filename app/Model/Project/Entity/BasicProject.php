<?php
namespace Backend\Model\Project\Entity;

use Backend\Contracts\Serializable;
use Backend\Enums\API\Response\Key\ProjectKey;
use Backend\Enums\API\Response\Key\UserKey;
use Backend\Model\Eloquent\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class BasicProject implements Serializable
{
    const STATUS_DRAFT         = 'draft';
    const STATUS_EXPERT_ONLY   = 'expert-only';
    const STATUS_PRIVATE       = 'private';
    const STATUS_DELETED       = 'deleted';
    const STATUS_NOT_EMAIL_OUT = 'not-yet-email-out';

    protected $data;

    /**
     * BasicProject constructor.
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->data[ProjectKey::KEY_ID];
    }

    /**
     * @return string
     */
    public function getUUID()
    {
        return $this->data[ProjectKey::KEY_UUID];
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->data[ProjectKey::KEY_TITLE];
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->data[ProjectKey::KEY_PROJECT_URL];
    }

    /**
     * @return string
     */
    public function getScheduleUrl()
    {
        return $this->data[ProjectKey::KEY_SCHEDULE_URL];
    }

    /**
     * @return array
     */
    public function getTags()
    {
        return $this->data[ProjectKey::KEY_TAGS];
    }

    /**
     * @param string   $glue
     * @param int|null $slice
     *
     * @return string
     */
    public function getTextTags(string $glue = ',', int $slice = null)
    {
        $collection = Collection::make($this->getTags());

        if (!is_null($slice)) {
            $collection->splice($slice);
        }

        return $collection->implode('text', $glue);
    }

    /**
     * @return string
     */
    public function getCategory()
    {
        return $this->data[ProjectKey::KEY_CATEGORY]['key'];
    }

    /**
     * @return string
     */
    public function getTextCategory()
    {
        return $this->data[ProjectKey::KEY_CATEGORY]['text'];
    }

    /**
     * @return string
     */
    public function getOwnerFullName()
    {
        $owner = $this->data[ProjectKey::KEY_OWNER];

        return $owner[UserKey::KEY_FIRST_NAME] . ' ' . $owner[UserKey::KEY_LAST_NAME];
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->data[ProjectKey::KEY_COUNTRY];
    }

    /**
     * @return string
     */
    public function getCompanyName()
    {
        return $this->data[ProjectKey::KEY_COMPANY_NAME];
    }

    /**
     * @return string
     */
    public function getCompanyUrl()
    {
        return $this->data[ProjectKey::KEY_COMPANY_URL];
    }

    /**
     * @return int
     */
    public function getOwnerId()
    {
        $owner = $this->data[ProjectKey::KEY_OWNER];

        return $owner[UserKey::KEY_USER_ID];
    }

    /**
     * @return string
     */
    public function getOwnerUrl()
    {
        $owner = $this->data[ProjectKey::KEY_OWNER];

        return $owner[UserKey::KEY_USER_URL];
    }

    /**
     * @return string
     */
    public function getOwnerLocation()
    {
        $owner = $this->data[ProjectKey::KEY_OWNER];

        return $owner[UserKey::KEY_LOCATION];
    }

    /**
     * @return string
     */
    public function getOwnerCompanyName()
    {
        $owner = $this->data[ProjectKey::KEY_OWNER];

        return $owner[UserKey::KEY_COMPANY_NAME];
    }

    /**
     * @return string
     */
    public function getOwnerJobTitle()
    {
        $owner = $this->data[ProjectKey::KEY_OWNER];

        return $owner[UserKey::KEY_JOB_TITLE];
    }

    /**
     * @return string
     */
    public function getOwnerPictureUrl()
    {
        $owner = $this->data[ProjectKey::KEY_OWNER];

        return $owner[UserKey::KEY_PICTURE_URL];
    }

    /**
     * @return bool
     */
    public function ownerIsExpert()
    {
        $owner = $this->data[ProjectKey::KEY_OWNER];

        return $owner[UserKey::KEY_USER_TYPE] === User::TYPE_EXPERT;
    }

    /**
     * @return array
     */
    public function getUserInfo()
    {
        return [
            'user_id'   => $this->getOwnerId(),
            'full_name' => $this->getOwnerFullName(),
            'image'     => $this->getOwnerPictureUrl(),
            'link'      => $this->getOwnerUrl(),
            'company'   => $this->getOwnerCompanyName(),
            'position'  => $this->getOwnerJobTitle(),
            'is_expert' => $this->ownerIsExpert()
        ];
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->data[ProjectKey::KEY_STATUS];
    }

    /**
     * @return string
     */
    public function getTextStatus()
    {
        if ($this->getStatus() === self::STATUS_DRAFT) {
            return 'Unfinished Draft';
        }

        switch ($this->getStatus()) {
            case self::STATUS_DRAFT:
                return 'Unfinished Draft';
            case self::STATUS_EXPERT_ONLY:
                return 'Expert Mode';
            case self::STATUS_PRIVATE:
                return 'Private Mode';
            case self::STATUS_DELETED:
                return 'Deleted';
            default:
                return $this->getStatus();
        }
    }

    /**
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->data[ProjectKey::KEY_CREATED_AT];
    }

    /**
     * @return null|string
     */
    public function getTextCreatedAt()
    {
        if (is_null($this->getCreatedAt())) {
            return null;
        }

        return Carbon::parse($this->getCreatedAt())->toFormattedDateString();
    }

    /**
     * @return string
     */
    public function getLastUpdatedAt()
    {
        return $this->data[ProjectKey::KEY_UPDATED_AT];
    }

    /**
     * @return null|string
     */
    public function getTextLastUpdatedAt()
    {
        if (is_null($this->getLastUpdatedAt())) {
            return null;
        }

        return Carbon::parse($this->getLastUpdatedAt())->toFormattedDateString();
    }

    /**
     * @return string
     */
    public function getDeletedAt()
    {
        return $this->data[ProjectKey::KEY_DELETED_AT];
    }

    /**
     * @return null|string
     */
    public function getTextDeletedAt()
    {
        if (is_null($this->getDeletedAt())) {
            return null;
        }

        return Carbon::parse($this->getDeletedAt())->toFormattedDateString();
    }

    /**
     * @return string
     */
    public function getSubmittedAt()
    {
        return $this->data[ProjectKey::KEY_SUBMITTED_AT];
    }

    /**
     * @return null|string
     */
    public function getTextSubmittedAt()
    {
        if (is_null($this->getSubmittedAt())) {
            return null;
        }

        return Carbon::parse($this->getSubmittedAt())->toFormattedDateString();
    }

    /**
     * @return null|string
     */
    public function getDeletedReason()
    {
        return $this->data[ProjectKey::KEY_DELETED_REASON];
    }

    /**
     * @return null|string
     */
    public function getRecommendedAt()
    {
        $info = $this->data[ProjectKey::KEY_RECOMMEND_EXPERT_INFO];

        if (empty($info)) {
            return null;
        }

        return $info[ProjectKey::KEY_RECOMMENDED_AT];
    }

    /**
     * @return null|string
     */
    public function getTextRecommendedAt()
    {
        if (is_null($this->getRecommendedAt())) {
            return null;
        }

        return Carbon::parse($this->getRecommendedAt())->toFormattedDateString();
    }

    /**
     * @return null|string
     */
    public function getRecommender()
    {
        $info = $this->data[ProjectKey::KEY_RECOMMEND_EXPERT_INFO];

        if (empty($info)) {
            return null;
        }

        return $info[ProjectKey::KEY_RECOMMENDER];
    }

    /**
     * @return bool
     */
    public function canRecommendExperts()
    {
        $created_at = new \DateTime($this->getCreatedAt());
        $show_date  = new \DateTime(env('SHOW_DATE'));

        if ($this->isApprovedSchedule()
            and $created_at > $show_date
            and ($this->isPublic() or $this->isPrivate())
            and is_null($this->getRecommendedAt())
        ) {
            return true;
        }

        return false;
    }

    /**
     * @return string
     */
    public function getLastEditorName()
    {
        return $this->data[ProjectKey::KEY_LAST_EDITOR_FIRST_NAME];
    }

    /**
     * @return string
     */
    public function getLastEditorUrl()
    {
        return $this->data[ProjectKey::KEY_LAST_EDITOR_URL];
    }

    /**
     * @return PM[]|array
     */
    public function getPMs()
    {
        $assigned_pms = $this->data[ProjectKey::KEY_ASSIGNED_PM];

        if (empty($assigned_pms)) {
            return [];
        }

        $result = [];
        foreach ($assigned_pms as $pm) {
            $result[] = PM::denormalize($pm);
        }

        return $result;
    }

    /**
     * @return array
     */
    public function getPMIds()
    {
        $assigned_pms = $this->data[ProjectKey::KEY_ASSIGNED_PM];

        if (empty($assigned_pms)) {
            return [];
        }

        $result = [];
        foreach ($assigned_pms as $pm) {
            $result[] = $pm[ProjectKey::KEY_USER_ID];
        }

        return $result;
    }

    /**
     * @return null|string
     */
    public function getMemoDescription()
    {
        $memo = $this->data[ProjectKey::KEY_MEMO];

        if (empty($memo)) {
            return null;
        }

        return $memo[ProjectKey::KEY_DESCRIPTION];
    }

    /**
     * @return null|string
     */
    public function getMemoScheduleNote()
    {
        $memo = $this->data[ProjectKey::KEY_MEMO];

        if (empty($memo)) {
            return null;
        }

        return $memo[ProjectKey::KEY_SCHEDULE_NOTE];
    }

    /**
     * @return null|string
     */
    public function getMemoScheduleNoteGrade()
    {
        $memo = $this->data[ProjectKey::KEY_MEMO];

        if (empty($memo)) {
            return null;
        }

        return $memo[ProjectKey::KEY_SCHEDULE_NOTE_GRADE];
    }

    /**
     * @return string
     */
    public function getTextMemoScheduleNoteGrade()
    {
        $options = [
            'not-graded' => 'Not graded',
            'A'          => 'Grade A',
            'B'          => 'Grade B',
            'C'          => 'Grade C ',
            'D'          => 'Grade D',
            'pending'    => 'Pending'
        ];

        return $options[$this->getMemoScheduleNoteGrade()];
    }

    /**
     * @return array
     */
    public function getMemoTags()
    {
        $memo = $this->data[ProjectKey::KEY_MEMO];

        if (empty($memo)) {
            return [];
        }

        return $memo[ProjectKey::KEY_TAGS];
    }

    /**
     * @param string   $glue
     * @param int|null $slice
     *
     * @return string
     */
    public function getTextMemoTags(string $glue = ',', int $slice = null)
    {
        $collection = Collection::make($this->getMemoTags());

        if (!is_null($slice)) {
            $collection->splice($slice);
        }

        return $collection->implode($glue);
    }

    /**
     * @return null|string
     */
    public function getMemoReportAction()
    {
        $memo = $this->data[ProjectKey::KEY_MEMO];

        if (empty($memo)) {
            return null;
        }

        return $memo[ProjectKey::KEY_REPORT_ACTION];
    }

    /**
     * @return int
     */
    public function countPageView()
    {
        $statistics = $this->data[ProjectKey::KEY_STATISTICS];

        return $statistics[ProjectKey::KEY_PAGE_VIEW];
    }

    /**
     * @return int
     */
    public function countStaffReferred()
    {
        $statistics = $this->data[ProjectKey::KEY_STATISTICS];

        return $statistics[ProjectKey::KEY_STAFF_REFERRALS];
    }

    /**
     * @return int
     */
    public function countCollaborators()
    {
        $statistics = $this->data[ProjectKey::KEY_STATISTICS];

        return $statistics[ProjectKey::KEY_COLLABORATORS];
    }

    /**
     * @return bool
     */
    public function isDeleted()
    {
        return $this->getStatus() === 'deleted';
    }

    /**
     * @return bool
     */
    public function isDraft()
    {
        return $this->getStatus() === 'draft';
    }

    /**
     * @return bool
     */
    public function isPublic()
    {
        return $this->getStatus() === 'expert-only';
    }

    /**
     * @return bool
     */
    public function isPrivate()
    {
        return $this->getStatus() === 'private';
    }

    /**
     * @return bool
     */
    public function isApprovedSchedule()
    {
        return $this->data[ProjectKey::KEY_IS_APPROVED_SCHEDULE];
    }

    /**
     * @return bool
     */
    public function isCreatedViaFusion360()
    {
        return $this->data[ProjectKey::KEY_IS_CREATED_VIA_FUSION360];
    }

    /**
     * {@inheritDoc}
     * @param array $data
     * @return BasicProject
     */
    public static function denormalize(array $data)
    {
        if (!is_array($data)) {
            throw new \InvalidArgumentException("'{$data}' is not a valid array format");
        }

        return new BasicProject($data);
    }

    /**
     * {@inheritDoc}
     * @param $serialized
     * @return BasicProject
     */
    public static function deserialize($serialized)
    {
        $result = json_decode($serialized, true);

        if (!is_array($result)) {
            throw new \InvalidArgumentException("'{$serialized}' is not a valid array format");
        }

        return static::denormalize($result);
    }

    /**
     * {@inheritDoc}
     */
    public function normalize()
    {
        return $this->data;
    }

    /**
     * {@inheritDoc}
     */
    public function serialize()
    {
        return json_encode($this);
    }

    /**
     * {@inheritDoc}
     */
    public function jsonSerialize()
    {
        return $this->normalize();
    }
}
