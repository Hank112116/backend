<?php
namespace Backend\Model\Project\Entity;

use Backend\Contracts\Serializable;
use Backend\Enums\API\Response\Key\ProjectKey;
use Backend\Enums\API\Response\Key\UserKey;

class PM implements Serializable
{
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
        return $this->data[UserKey::KEY_USER_ID];
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->data[UserKey::KEY_FIRST_NAME];
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->data[UserKey::KEY_LAST_NAME];
    }

    /**
     * @return bool
     */
    public function isBackendPM()
    {
        return $this->data[ProjectKey::KEY_PM_TYPE] === 'backend';
    }

    /**
     * @return bool
     */
    public function isFrontendPM()
    {
        return $this->data[ProjectKey::KEY_PM_TYPE] === 'frontend';
    }

    /**
     * {@inheritDoc}
     * @param array $data
     * @return PM
     */
    public static function denormalize(array $data)
    {
        if (!is_array($data)) {
            throw new \InvalidArgumentException("'{$data}' is not a valid array format");
        }

        return new PM($data);
    }

    /**
     * {@inheritDoc}
     * @param $serialized
     * @return PM
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
