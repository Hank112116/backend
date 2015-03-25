<?php

/**
 * Certification types for roles
 * Refer scope document for detail
 **/

return [
    'all'             => [
        'Adminer'  => ['adminer' => 'Adminer Full'],
        'Member'   => ['user' => 'User Full', 'user_restricted' => 'User Limited'],
        'Project'  => ['project' => 'Project Full'],
        'Solution' => ['solution' => 'Solution Full', 'solution_restricted' => 'Solution Limited'],
        'Hub'      => ['hub_full' => 'Hub Full', 'hub_restricted' => 'Hub Limited', 'schedule_manager' => 'Schedule Manager'],
        'Other'    => ['email_template' => 'EMails Full', 'front_page' => 'Marketing'],
    ],
    'default_admin'   => [
        'adminer', 'user', 'project', 'solution', 'email_template', 'front_page',
    ],
    'default_manager' => [
        'user_restricted', 'solution_restricted',
    ]
];
