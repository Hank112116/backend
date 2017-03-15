<?php

namespace Backend\Model\Feature;

use Backend\Contracts\Serializable;

class FeatureEntity implements Serializable
{
    const KEY_OBJECT_TYPE = 'objectType';
    const KEY_ORDER       = 'order';


    const USER_TYPE     = 'user';
    const PROJECT_TYPE  = 'project';
    const SOLUTION_TYPE = 'solution';


    private $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function getOrder()
    {
        return $this->data[self::KEY_ORDER];
    }

    public function isUserObject()
    {
        return $this->data[self::KEY_OBJECT_TYPE] === self::USER_TYPE ? true : false;
    }

    public function isProjectObject()
    {
        return $this->data[self::KEY_OBJECT_TYPE] === self::PROJECT_TYPE ? true : false;
    }

    public function isSolutionObject()
    {
        return $this->data[self::KEY_OBJECT_TYPE] === self::SOLUTION_TYPE ? true : false;
    }

    public function getObjectId()
    {
        if ($this->isUserObject()) {
            $user = $this->data[self::USER_TYPE];

            return $user['id'];
        } elseif ($this->isProjectObject()) {
            $project = $this->data[self::PROJECT_TYPE];

            return $project['id'];
        } elseif ($this->isSolutionObject()) {
            $solution = $this->data[self::SOLUTION_TYPE];

            return $solution['id'];
        } else {
            return null;
        }
    }

    public function getObjectName()
    {
        if ($this->isUserObject()) {
            $user = $this->data[self::USER_TYPE];

            return $user['fullName'];
        } elseif ($this->isProjectObject()) {
            $project = $this->data[self::PROJECT_TYPE];

            return $project['name'];
        } elseif ($this->isSolutionObject()) {
            $solution = $this->data[self::SOLUTION_TYPE];

            return $solution['name'];
        } else {
            return null;
        }
    }

    public function getObjectOwner()
    {
        if ($this->isUserObject()) {
            $user = $this->data[self::USER_TYPE];

            return $user['companyName'];
        } elseif ($this->isProjectObject()) {
            $project = $this->data[self::PROJECT_TYPE];
            $owner   = $project['owner'];

            return $owner['fullName'];
        } elseif ($this->isSolutionObject()) {
            $solution = $this->data[self::SOLUTION_TYPE];
            $owner    = $solution['owner'];

            return $owner['fullName'];
        } else {
            return null;
        }
    }

    public function getObjectType()
    {
        if ($this->isUserObject()) {
            $user = $this->data[self::USER_TYPE];

            return $user['userType'];
        } elseif ($this->isProjectObject()) {
            return 'project';
        } elseif ($this->isSolutionObject()) {
            $solution = $this->data[self::SOLUTION_TYPE];

            return $solution['type'];
        } else {
            return null;
        }
    }

    public function getEntityBlockId()
    {
        if ($this->isUserObject()) {
            return 'expert_' . $this->getObjectId();
        } elseif ($this->isProjectObject()) {
            return 'project_' . $this->getObjectId();
        } elseif ($this->isSolutionObject()) {
            return 'solution_' . $this->getObjectId();
        } else {
            return null;
        }
    }

    public function getEntityBlockType()
    {
        if ($this->isUserObject()) {
            return 'expert';
        } elseif ($this->isProjectObject()) {
            return 'project';
        } elseif ($this->isSolutionObject()) {
            return 'solution';
        } else {
            return null;
        }
    }

    public function getTextObjectType()
    {
        if ($this->isUserObject()) {
            switch ($this->getObjectType()) {
                case 'expert':
                    return 'Expert';
                    break;
                case 'premium-expert':
                    return 'Premium Expert';
                    break;
                case 'creator':
                    return 'Creator';
                    break;
                case 'premium-creator':
                    return 'Premium Creator';
                    break;
                case 'pm':
                    return 'HWTrek PM';
                    break;
                default:
                    return null;
            }
        } elseif ($this->isProjectObject()) {
            return 'Project';
        } elseif ($this->isSolutionObject()) {
            switch ($this->getObjectType()) {
                case 'normal-solution':
                    return 'Solution';
                    break;
                case 'program':
                    return 'Program';
                    break;
                default:
                    return null;
            }
        } else {
            return null;
        }
    }

    public function getObjectStatus()
    {
        if ($this->isUserObject()) {
            $user = $this->data[self::USER_TYPE];

            return $user['status'];
        } elseif ($this->isProjectObject()) {
            $project = $this->data[self::PROJECT_TYPE];

            return $project['status'];
        } elseif ($this->isSolutionObject()) {
            $solution = $this->data[self::SOLUTION_TYPE];

            return $solution['status'];
        } else {
            return null;
        }
    }

    public function textFrontLink()
    {
        if ($this->isUserObject()) {
            $user = $this->data[self::USER_TYPE];

            return $user['userUrl'];
        } elseif ($this->isProjectObject()) {
            $project = $this->data[self::PROJECT_TYPE];

            return $project['projectUrl'];
        } elseif ($this->isSolutionObject()) {
            $solution = $this->data[self::SOLUTION_TYPE];

            return $solution['solutionUrl'];
        } else {
            return null;
        }
    }

    public function isDangerStatus()
    {
        if ($this->isUserObject()) {
            if ($this->getObjectStatus() === 'verified'
            and ($this->getObjectType() === 'expert' or $this->getObjectType() === 'premium-expert')) {
                return false;
            }
        } elseif ($this->isProjectObject()) {
            if ($this->getObjectStatus() === 'private' or $this->getObjectStatus() === 'expert-only') {
                return false;
            }
        } elseif ($this->isSolutionObject()) {
            if ($this->getObjectStatus() === 'on-shelf') {
                return false;
            }
        }

        return true;
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


        return new FeatureEntity($data);
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
