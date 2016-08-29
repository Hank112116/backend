<?php
namespace Backend\Enums;

class ProjectCategoryEnum
{
    const CATEGORY_OTHERS     = 0;
    const CATEGORY_WEARABLE   = 1;
    const CATEGORY_INDUSTRIAL = 2;
    const CATEGORY_SPORTS     = 3;
    const CATEGORY_TOYS       = 4;
    const CATEGORY_CAMERAS    = 5;
    const CATEGORY_FAMILY     = 6;
    const CATEGORY_MOBILE     = 7;
    const CATEGORY_AUTO       = 8;
    const CATEGORY_HEALTH     = 9;
    const CATEGORY_SCIENCE    = 10;

    const CATEGORIES = [
        self::CATEGORY_WEARABLE   => 'Wearable',
        self::CATEGORY_INDUSTRIAL => 'Industrial Applications',
        self::CATEGORY_SPORTS     => 'Sports Devices',
        self::CATEGORY_TOYS       => 'Toys / Games',
        self::CATEGORY_CAMERAS    => 'Audio & Video Devices',
        self::CATEGORY_FAMILY     => 'Smart Home Devices',
        self::CATEGORY_MOBILE     => 'Tech Accessories',
        self::CATEGORY_AUTO       => 'Auto Applications',
        self::CATEGORY_HEALTH     => 'Health Devices',
        self::CATEGORY_SCIENCE    => 'Science Gadgets',
        self::CATEGORY_OTHERS     => 'Others'

    ];
}
