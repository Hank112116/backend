<?php

namespace Backend\Model\LowPriorityObject;

use Backend\Contracts\Serializable;

class LowPrioritySolution implements Serializable
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

    public function title()
    {
        return $this->data['name'];
    }

    public function status()
    {
        return $this->data['status'];
    }

    public function ownerFullName()
    {
        $owner = $this->data['owner'];

        return $owner['fullName'];
    }

    public function ownerUrl()
    {
        $owner = $this->data['owner'];

        return $owner['userUrl'];
    }

    public function url()
    {
        return $this->data['solutionUrl'];
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

        return new LowPrioritySolution($data);
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
