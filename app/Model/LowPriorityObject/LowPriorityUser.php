<?php

namespace Backend\Model\LowPriorityObject;

use Backend\Contracts\Serializable;

class LowPriorityUser implements Serializable
{
    private $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function id()
    {
        return $this->data['id'];
    }

    public function fullName()
    {
        return $this->data['fullName'];
    }

    public function userType()
    {
        return $this->data['userType'];
    }

    public function textUserType()
    {
        switch ($this->userType()) {
            case 'creator':
                return 'Creator';
            case 'expert':
                return 'Expert';
            case 'premium-creator':
                return 'Premium Creator';
            case 'premium-expert':
                return 'Premium Expert';
            case 'pm':
                return 'HWTrek PM';
            default:
                return 'N/A';
        }
    }

    public function status()
    {
        return $this->data['status'];
    }

    public function companyName()
    {
        return $this->data['companyName'];
    }

    public function position()
    {
        return $this->data['position'];
    }

    public function url()
    {
        return $this->data['userUrl'];
    }

    public function normalize()
    {
        return $this->data;
    }

    public static function denormalize(array $data)
    {
        if (!is_array($data)) {
            throw new \InvalidArgumentException("'{$data}' is not a valid array format");
        }

        return new LowPriorityUser($data);
    }

    public function serialize()
    {
        return json_encode($this);
    }

    public static function deserialize($serialized)
    {
        $result = json_decode($serialized, true);

        if (!is_array($result)) {
            throw new \InvalidArgumentException("'{$serialized}' is not a valid array format");
        }

        return static::denormalize($result);
    }

    public function jsonSerialize()
    {
        return $this->normalize();
    }
}
