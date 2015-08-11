<?php

/**
 * Certification types for roles
 * Refer scope document for detail
 **/

return [
    'all'             => [
        'Adminer'   => ['adminer' => 'Adminer Full'],
        'Member'    => ['user' => 'User Full', 'user_restricted' => 'User Limited'],
        'Project'   => ['project' => 'Project Full'],
        'Solution'  => ['solution' => 'Solution Full', 'solution_restricted' => 'Solution Limited'],
        'Hub'       => ['hub_full' => 'Hub Full', 'hub_restricted' => 'Hub Limited', 'schedule_manager' => 'Schedule Manager'],
        'Marketing' => ['marketing_full' => 'Marketing Full' , 'marketing_expert_list' => 'Home Expert List'],
        'Report'    => ['report' => 'Report Full'],
        'Other'     => ['email_template' => 'EMails Full', 'front_page' => 'Marketing' ],
    ],
    'default_admin'   => [
        'adminer', 'user', 'project', 'solution', 'email_template', 'front_page', 'marketing',
    ],
    'default_manager' => [
        'user_restricted', 'solution_restricted',
    ]
];
