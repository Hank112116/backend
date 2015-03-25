<?php

namespace Backend\Model\Eloquent;

/**
 * no primary key in table
 * @table editing_perk
 **/
class DuplicatePerk extends Perk
{

    protected $table = 'editing_perk';
    protected $primaryKey = 'perk_id';
}
