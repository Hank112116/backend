<?php

namespace Backend\Model\Eloquent;

use Illuminate\Database\Eloquent\Model as Eloquent;

class ProjectCategory extends Eloquent
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

    private static $categories = [
        self::CATEGORY_WEARABLE   => 'Wearables',
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

    const DEFAULT_TYPE = '2';

    protected $table = 'tag';
    protected $primaryKey = 'tag_id';

    private $solution_looking_categories = null;
    private $solution_looking_categoriy_other = null;

    public function categories()
    {
        return self::$categories;
    }

    public function parseSolutionLookingCategory($category_implode, $category_other)
    {
        $this->parseCategory($category_implode);

        if (mb_strlen($category_other) > 0) {
            $this->solution_looking_categoriy_other = $category_other;
        }
    }

    private function parseCategory($category_implode)
    {
        $this->solution_looking_categories = [];

        if (mb_strlen($category_implode) == 0) {
            return;
        }

        foreach (explode(',', $category_implode) as $category) {
            if (array_key_exists($category, self::$categories)) {
                $this->solution_looking_categories[] = [
                    'id'   => $category,
                    'text' => self::$categories[$category]
                ];
            }
        }
    }

    public function solutionLookingCategories()
    {
        $result = $this->solution_looking_categories;

        if ($this->solution_looking_categoriy_other) {
            $result[] = [
                'id'   => '',
                'text' => $this->solution_looking_categoriy_other
            ];
        }

        return $result;
    }

    public function solutionContains($category_id)
    {
        return in_array($category_id, array_pluck($this->solution_looking_categories, 'id'));
    }
}
