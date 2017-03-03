<?php

namespace Backend\Enums;

class ObjectType
{
    const USER_TYPE     = 'user';
    const PROJECT_TYPE  = 'project';
    const SOLUTION_TYPE = 'solution';

    const OBJECT_TYPE = [
        self::USER_TYPE,
        self::PROJECT_TYPE,
        self::SOLUTION_TYPE,
    ];
}
