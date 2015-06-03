<?php

namespace Backend\Model\Eloquent;

class Industry
{

    const CATEG_ID = 'category_id';
    const CATEG_NAME = 'category_name';
    const CATEG_FULLNAME = 'category_full_name';
    const CATEG_NAME_TRANSLATED = 'category_name_translated';
    const CATEG_FULLNAME_TRANSLATED = 'category_full_name_translated';
    const CATEG_URL_NAME = 'category_url_name';
    const CATEG_IS_VIRTUAL = 'category_is_virtual';
    const CATEG_PARENT_ID = 'category_parent_id';
    const DEFAULT_USER_TAG_TYPE = '1';
    const DEFAULT_PROJECT_TAG_TYPE = '2';
    const DEFAULT_SOLUTION_TAG_TYPE = '3';

    private static $categories = [
        11 => [self::CATEG_NAME_TRANSLATED => 'Marketing'],
        12 => [self::CATEG_NAME_TRANSLATED => 'Manufacture'],
        13 => [self::CATEG_NAME_TRANSLATED => 'IC / Design house'],
        14 => [self::CATEG_NAME_TRANSLATED     => 'EMS',
               self::CATEG_FULLNAME_TRANSLATED => 'EMS (electronic manufacturing services)'],
        15 => [self::CATEG_NAME_TRANSLATED     => 'OEM',
               self::CATEG_FULLNAME_TRANSLATED => 'OEM (original equipment manufacturer)'],
        16 => [self::CATEG_NAME_TRANSLATED     => 'ODM',
               self::CATEG_FULLNAME_TRANSLATED => 'ODM (original design manufacturer)'],
        17 => [self::CATEG_NAME_TRANSLATED => 'Components distributor'],
        18 => [self::CATEG_NAME_TRANSLATED => 'Mechanical'],
        19 => [self::CATEG_NAME_TRANSLATED => 'Wholesale'],
        20 => [self::CATEG_NAME_TRANSLATED => 'Venture Capital'],
        21 => [self::CATEG_NAME_TRANSLATED => 'Consulting / Agent'],
        22 => [self::CATEG_NAME_TRANSLATED => 'Others']
    ];

    public static function getUpdateArray()
    {
        $cate  = self::$categories;
        $brief = self::CATEG_NAME_TRANSLATED;

        return [
            11 => $cate[11][$brief],
            12 => [
                $cate[12][$brief] =>
                    [13 => $cate[13][$brief], 14 => $cate[14][$brief],
                     15 => $cate[15][$brief], 16 => $cate[16][$brief],
                     17 => $cate[17][$brief], 18 => $cate[18][$brief]]
            ],
            19 => $cate[19][$brief],
            20 => $cate[20][$brief],
            21 => $cate[21][$brief],
            22 => $cate[22][$brief]
        ];
    }

    public static function parseToArray($category_ids = '')
    {
        $result = [];
        foreach (explode(',', $category_ids) as $key) {
            if (array_key_exists($key, self::$categories)) {
                $result[] = self::$categories[$key][self::CATEG_NAME_TRANSLATED];
            }
        }

        return $result;
    }
}
