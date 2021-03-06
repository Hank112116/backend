<?php

namespace Backend\Enums\URI\API\HWTrek;

use Backend\Enums\URI\API\HWTrekApiEnum;

class UserApiEnum extends HWTrekApiEnum
{
    /*
     * User
     */
    const USER                   = self::API . '/users/(:num)';
    const USER_BACKEND           = self::API . self::BACKEND . '/users/(:num)';
    const ATTACHMENT             = self::USER_BACKEND . '/attachments';
    const PRIVILEGE              = self::USER . '/privilege';
    const TRANSFORM_ACCOUNT_TYPE = self::USER . '/account-type';
    const UPLOAD_COMPANY_LOGO    = self::USER . '/company-logo';
}
