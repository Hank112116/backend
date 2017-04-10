<?php

namespace Backend\Enums\URI\API\HWTrek;

use Backend\Enums\URI\API\HWTrekApiEnum;

class MarketingApiEnum extends HWTrekApiEnum
{
    const MARKETING_API = self::API . self::MARKETING;
    const FEATURES      = self::MARKETING_API . '/features';

    const LOW_PRIORITY_USERS     = self::MARKETING_API . '/low-priority-users';
    const LOW_PRIORITY_PROJECTS  = self::MARKETING_API . '/low-priority-projects';
    const LOW_PRIORITY_SOLUTIONS = self::MARKETING_API . '/low-priority-solutions';
}
