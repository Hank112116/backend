<?php

namespace Backend\Enums\URI\API\HWTrek;

use Backend\Enums\URI\API\HWTrekApiEnum;

class MarketingApiEnum extends HWTrekApiEnum
{
    const MARKETING_API = self::API . self::MARKETING;
    const FEATURES      = self::MARKETING_API . '/features';
}
