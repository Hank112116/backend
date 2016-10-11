<?php

namespace Backend\Enums\URI\API\HWTrek;

use Backend\Enums\URI\API\HWTrekApiEnum;

class SolutionApiEnum extends HWTrekApiEnum
{
    /**
     * Solution
     */
    const SOLUTION       = self::API . '/solutions/(:num)';
    const APPROVE        = self::SOLUTION . '/approve';
    const REJECT_APPROVE = self::SOLUTION . '/reject-approve';
}
