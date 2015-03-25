<?php

namespace Backend\Model\Eloquent;
use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 *
 * @refer  hwp/application/schema/expertise_category.php
 * @table   expertise_category
 * @pk      expertise_id
 * @columns full_name, short_name, type, tag, parent_id
 **/

class Expertise extends Eloquent
{

    protected $table = 'expertise_category';
    protected $primaryKey = 'expertise_id';

    public function getExpertiseTagsArray($ids = [])
    {
        if (!$ids) {
            return [];
        }

        return $this->whereIn('expertise_id', $ids)->lists('tag');
    }
}
