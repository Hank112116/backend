<?php

namespace Backend\Enums\URI\API\HWTrek;

use Backend\Enums\URI\API\HWTrekApiEnum;

class SolutionApiEnum extends HWTrekApiEnum
{
    /**
     * Solution
     */
    const SOLUTIONS  = self::API . '/solutions';
    const SOLUTION   = self::API . '/solutions/(:num)';
    const PUBLICITY  = self::SOLUTION . '/publicity';
    const TYPE       = self::SOLUTION . '/type';
}
