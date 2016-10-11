<?php

namespace Backend\Enums\URI\API\HWTrek;

use Backend\Enums\URI\API\HWTrekApiEnum;

class ProjectApiEnum extends HWTrekApiEnum
{
    /*
     * Project
     */
    const PROJECT                 = self::API . '/projects/(:any)';
    const PROJECT_STATISTICS      = self::API . '/project-statistics/(:any)';
    const RELEASE                 = self::PROJECT . '/release';
    const STAFF_RECOMMEND_EXPERTS = self::PROJECT . '/staff-recommend-experts';
}
