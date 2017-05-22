<?php

namespace Backend\Enums\URI\API\HWTrek;

use Backend\Enums\URI\API\HWTrekApiEnum;

class ProjectApiEnum extends HWTrekApiEnum
{
    /*
     * Project
     */
    const PROJECTS                = self::API      . '/projects';
    const PROJECT                 = self::PROJECTS . '/(:any)';
    const RELEASE                 = self::PROJECT  . '/release';
    const STAFF_RECOMMEND_EXPERTS = self::PROJECT  . '/staff-recommend-experts';
    const ASSIGN_PMS              = self::PROJECT  . '/assign-pms';
    const MEMO                    = self::PROJECT  . '/memo';
    const MODE                    = self::PROJECT  . '/mode';
}
