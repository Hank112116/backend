<?php
namespace Backend\Model\Solution\Entity;

use Backend\Contracts\Serializable;
use Backend\Enums\API\Response\Key\SolutionKey;
use Backend\Enums\API\Response\Key\UserKey;
use Backend\Model\Eloquent\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class BasicSolution implements Serializable
{
    const TYPE_NORMAL_SOLUTION  = 'normal-solution';
    const TYPE_PROGRAM          = 'program';
    const TYPE_PENDING_SOLUTION = 'pending-to-normal-solution';
    const TYPE_PENDING_PROGRAM  = 'pending-to-program';
    const STATUS_ARCHIVED       = 'archived';

    protected $data;

    /**
     * BasicSolution constructor.
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
        return $this->data[SolutionKey::KEY_ID];
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->data[SolutionKey::KEY_NAME];
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->data[SolutionKey::KEY_SOLUTION_URL];
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->data[SolutionKey::KEY_TYPE];
    }

    /**
     * @return string
     */
    public function getSummary()
    {
        return $this->data[SolutionKey::KEY_SUMMARY];
    }

    /**
     * @return array
     */
    public function getTag()
    {
        return $this->data[SolutionKey::KEY_TAG];
    }

    /**
     * @return string
     */
    public function getTextTags()
    {
        $collection = Collection::make($this->getTag());

        return $collection->implode('text', ',');
    }

    /**
     * @return string
     */
    public function getTextType()
    {
        switch ($this->getType()) {
            case self::TYPE_NORMAL_SOLUTION:
                return 'Solution';

            case self::TYPE_PROGRAM:
                return 'Program';

            case self::TYPE_PENDING_SOLUTION:
                return 'Pending Solution';

            case self::TYPE_PENDING_PROGRAM:
                return 'Pending Program';
            default:
                return 'N/A';
        }
    }

    /**
     * @return string
     */
    public function getCategory()
    {
        return $this->data[SolutionKey::KEY_CATEGORY]['key'];
    }

    /**
     * @return string
     */
    public function getTextCategory()
    {
        return $this->data[SolutionKey::KEY_CATEGORY]['text'];
    }

    /**
     * @return string
     */
    public function getSubCategory()
    {
        return $this->data[SolutionKey::KEY_SUB_CATEGORY]['key'];
    }

    /**
     * @return string
     */
    public function getTextSubCategory()
    {
        return $this->data[SolutionKey::KEY_SUB_CATEGORY]['text'];
    }

    /**
     * @return string
     */
    public function getOwnerFullName()
    {
        $owner = $this->data[SolutionKey::KEY_MEMBER];

        return $owner[UserKey::KEY_FIRST_NAME] . ' ' . $owner[UserKey::KEY_LAST_NAME];
    }

    /**
     * @return int
     */
    public function getOwnerId()
    {
        $owner = $this->data[SolutionKey::KEY_MEMBER];

        return $owner[UserKey::KEY_USER_ID];
    }

    /**
     * @return string
     */
    public function getOwnerUrl()
    {
        $owner = $this->data[SolutionKey::KEY_MEMBER];

        return $owner[UserKey::KEY_USER_URL];
    }

    /**
     * @return string
     */
    public function getOwnerLocation()
    {
        $owner = $this->data[SolutionKey::KEY_MEMBER];

        return $owner[UserKey::KEY_LOCATION];
    }

    /**
     * @return string
     */
    public function getOwnerCompanyName()
    {
        $owner = $this->data[SolutionKey::KEY_MEMBER];

        return $owner[UserKey::KEY_COMPANY_NAME];
    }

    /**
     * @return string
     */
    public function getOwnerJobTitle()
    {
        $owner = $this->data[SolutionKey::KEY_MEMBER];

        return $owner[UserKey::KEY_JOB_TITLE];
    }

    /**
     * @return string
     */
    public function getOwnerPictureUrl()
    {
        $owner = $this->data[SolutionKey::KEY_MEMBER];

        return $owner[UserKey::KEY_PICTURE_URL];
    }

    /**
     * @return bool
     */
    public function ownerIsExpert()
    {
        $owner = $this->data[SolutionKey::KEY_MEMBER];

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
        return $this->data[SolutionKey::KEY_STATUS];
    }

    /**
     * @return string
     */
    public function getTextStatus()
    {
        if ($this->getStatus() === self::STATUS_ARCHIVED) {
            return 'Deleted';
        }

        return $this->getStatus();
    }

    /**
     * @return string
     */
    public function getApprovedDate()
    {
        return $this->data[SolutionKey::KEY_APPROVED_AT];
    }

    /**
     * @return null|string
     */
    public function getTextApprovedDate()
    {
        if (is_null($this->getApprovedDate())) {
            return null;
        }

        return Carbon::parse($this->getApprovedDate())->toFormattedDateString();
    }

    /**
     * @return string
     */
    public function getLastUpdateDate()
    {
        return $this->data[SolutionKey::KEY_UPDATED_AT];
    }

    /**
     * @return null|string
     */
    public function getTextLastUpdateDate()
    {
        if (is_null($this->getLastUpdateDate())) {
            return null;
        }

        return Carbon::parse($this->getLastUpdateDate())->toFormattedDateString();
    }

    /**
     * @return string
     */
    public function getDeletedAt()
    {
        return $this->data[SolutionKey::KEY_DELETED_AT];
    }

    /**
     * @return null|string
     */
    public function getTextDeleteDate()
    {
        if (is_null($this->getDeletedAt())) {
            return null;
        }

        return Carbon::parse($this->getDeletedAt())->toFormattedDateString();
    }

    /**
     * @return bool
     */
    public function isManagerApproved()
    {
        return $this->data[SolutionKey::KEY_IS_MANAGER_APPROVED];
    }

    /**
     * @return bool
     */
    public function isWaitPublish()
    {
        return $this->data[SolutionKey::KEY_IS_WAIT_PUBLISH];
    }

    /**
     * @return bool
     */
    public function isSolution()
    {
        return $this->getType() === self::TYPE_NORMAL_SOLUTION;
    }

    /**
     * @return bool
     */
    public function isProgram()
    {
        return $this->getType() === self::TYPE_PROGRAM;
    }

    /**
     * @return bool
     */
    public function isPendingSolution()
    {
        return $this->getType() === self::TYPE_PENDING_SOLUTION;
    }

    /**
     * @return bool
     */
    public function isPendingProgram()
    {
        return $this->getType() === self::TYPE_PENDING_PROGRAM;
    }

    /**
     * @return bool
     */
    public function isDeleted()
    {
        return $this->getStatus() === 'archived';
    }

    /**
     * @return bool
     */
    public function isOnShelf()
    {
        return $this->getStatus() === 'Available Solution';
    }

    /**
     * @return bool
     */
    public function isOffShelf()
    {
        return $this->getStatus() === 'Unavailable Solution';
    }

    /**
     * @return bool
     */
    public function isOngoing()
    {
        return $this->isOnShelf() or $this->isOffShelf();
    }

    /**
     * {@inheritDoc}
     * @param array $data
     * @return BasicSolution
     */
    public static function denormalize(array $data)
    {
        if (!is_array($data)) {
            throw new \InvalidArgumentException("'{$data}' is not a valid array format");
        }

        return new BasicSolution($data);
    }

    /**
     * {@inheritDoc}
     * @param $serialized
     * @return BasicSolution
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
